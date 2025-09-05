<?php

namespace App\Http\Controllers;

use App\Models\NetworkManagement;
use App\Models\Ip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NetworkProvisioningController extends Controller
{
    public function create()
    {
        return view('network-provisioning.create');
    }

    public function store(Request $request)
    {
        $randomPassword = \Illuminate\Support\Str::random(12);

        // Check if this is a duplicate submission using session
        $submissionKey = 'network_provisioning_' . md5(serialize($request->all()));
        if (session()->has($submissionKey)) {
            // If this is a duplicate submission, get the stored data and return view without storing
            $storedData = session($submissionKey);
            return view('network-provisioning.pfsense', [
                'propertyName' => $storedData['propertyName'],
                'ipAssignments' => $storedData['ipAssignments'],
                'xmlFile' => $storedData['xmlFile'],
                'randomPassword' => $storedData['randomPassword'],
                'provisionId'   => $storedData['provisionId']
            ]);
        }

        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'oem' => 'nullable|string|max:255',
            'property_address' => 'nullable|string|max:255',
            'remote_unit_quantity' => 'nullable|integer',
            'master_unit_quantity' => 'nullable|integer|min:0',
            'bda_quantity' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'property_type' => 'nullable|string|max:255',
            'average_density' => 'nullable|string|max:255',
            'system_type' => 'nullable|string|max:255',
            'das_equipment' => 'nullable|string|max:255',
            'errcs_equipment' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|string|max:255',
            'random_password' => 'nullable|string|max:255',
            'static_ip'        => 'nullable|string|max:150'
        ]);
        
        

        // Additional validation based on system type
        $systemType = $validated['system_type'] ?? '';
        if (strtolower($systemType) === 'das') {
            if (empty($validated['master_unit_quantity']) && $validated['master_unit_quantity'] !== 0) {
                return back()->withErrors(['master_unit_quantity' => 'Master Unit Quantity is required for DAS system type.']);
            }
        } elseif (strtolower($systemType) === 'errcs') {
            if (empty($validated['bda_quantity']) && $validated['bda_quantity'] !== 0) {
                return back()->withErrors(['bda_quantity' => 'BDA Quantity is required for ERRCS system type.']);
            }
        } elseif (strtolower($systemType) === 'das and errcs') {
            if (empty($validated['master_unit_quantity']) && $validated['master_unit_quantity'] !== 0) {
                return back()->withErrors(['master_unit_quantity' => 'Master Unit Quantity is required for DAS & ERRCS system type.']);
            }
            if (empty($validated['bda_quantity']) && $validated['bda_quantity'] !== 0) {
                return back()->withErrors(['bda_quantity' => 'BDA Quantity is required for DAS & ERRCS system type.']);
            }
        }

        $networkManagement = NetworkManagement::create($validated);
        $provisionId = $networkManagement->id;

        $ipRow = Ip::where('in_use', false)->first();
        if (!$ipRow) {
            return back()->with('error', 'No available IP ranges.');
        }
        
        // Ensure the IP row has a valid first_usable_ip
        if (empty($ipRow->first_usable_ip)) {
            return back()->with('error', 'Invalid IP range configuration.');
        }
        
        Log::info('Using IP range with first_usable_ip: ' . $ipRow->first_usable_ip);
        
        $ipRow->in_use = true;
        $ipRow->save();

        // Get quantities from form (default to 0 if not set)
        $masterQty = (int) $request->input('master_unit_quantity', 0);
        $bdaQty = (int) $request->input('bda_quantity', 0);

        

        // Helper to increment IP
        function ip_add($ip, $increment) {
            $ipLong = ip2long($ip);
            return long2ip($ipLong + $increment);
        }

        // Build assignments array
        $ipAssignments = [];
        $currentIncrement = 1;

        // Master Unit Sectors
        for ($i = 1; $i <= $masterQty; $i++) {
            $ipAssignments[] = [
                'label' => "Master Unit Sector $i",
                'ip' => ip_add($ipRow->first_usable_ip, $currentIncrement),
                'mask' => '255.255.255.192',
            ];
            $currentIncrement++;
        }

        // ERRCS BDA
        for ($i = 1; $i <= $bdaQty; $i++) {
            $ipAssignments[] = [
                'label' => "ERRCS BDA $i",
                'ip' => ip_add($ipRow->first_usable_ip, $currentIncrement),
                'mask' => '255.255.255.192',
            ];
            $currentIncrement++;
        }
        
        // Log the IP assignments for debugging
        Log::info('IP Assignments created:', $ipAssignments);

        
        // Novos campos do formulário
        $dyndnsHostname = $request->input('hostname', '');
        $isStaticIp = $request->has('static_ip_check');
        $staticIp = $request->input('static_ip', '');
        $staticMask = $request->input('static_mask', '');
        $static_gateway = $request->input('static_gateway', '');

        // Busca o template XML sempre pelo registro de id=1
        $templateRow = \App\Models\xmlTemplate::find(1);
        $templateString = $templateRow ? $templateRow->content : '<config>#propertyName#</config>';

        // Gerar senha aleatória
        

        // Substituição dos placeholders conforme regras
        $placeholders = [
            '#system.hostname#'   => $validated['property_name'],
            '#ipsec.hostname#'    => $validated['property_name'],
            '#dyndns.hostname#'   => $dyndnsHostname,
            '#random.password#'   => $randomPassword,
        ];

        if ($isStaticIp) {
            $placeholders['#wan.ipaddr#'] = $staticIp;
            $placeholders['#wan.mask#']   = $staticMask;
            $placeholders['#lan.ipaddr#'] = ip_add($ipRow->first_usable_ip, 0);
            $placeholders['#gateway#']    = $static_gateway;
        } else {
            $placeholders['#wan.ipaddr#'] = '';
            $placeholders['#wan.mask#']   = '';
            $placeholders['#lan.ipaddr#'] = ip_add($ipRow->first_usable_ip, 0); 
            ##$ipRows->first_usable_ip ?? '';
        }

        // Substituir todos os placeholders
        foreach ($placeholders as $key => $value) {
            $templateString = str_replace($key, $value, $templateString);
        }

        // Salvar arquivo XML
        $xmlFileName = 'config_file_' . $validated['property_name'] . '.xml';
        \Illuminate\Support\Facades\Storage::disk('local')->put('xml/' . $xmlFileName, $templateString);

        // Determine the first_usable_ip based on system type
        $firstUsableIp = null;
        $systemType = $validated['system_type'] ?? '';
        
        // Log the system type and quantities for debugging
        Log::info('System Type: ' . $systemType . ', Master Qty: ' . $masterQty . ', BDA Qty: ' . $bdaQty);
        Log::info('IP Assignments count: ' . count($ipAssignments));
        
        if (strtolower($systemType) === 'das' || strtolower($systemType) === 'das and errcs') {
            // For DAS or DAS and ERRCS, use Master Unit Sector 1 IP
            if (!empty($ipAssignments)) {
                foreach ($ipAssignments as $assignment) {
                    if (strpos($assignment['label'], 'Master Unit Sector 1') !== false) {
                        $firstUsableIp = $assignment['ip'];
                        Log::info('Found Master Unit Sector 1 IP: ' . $firstUsableIp);
                        break;
                    }
                }
            }
            
            // If no IP assignment found in the array (e.g., quantities are 0), 
            // use the first usable IP from the IP range
            if ($firstUsableIp === null) {
                $firstUsableIp = ip_add($ipRow->first_usable_ip, 1);
                Log::info('Using fallback IP for DAS/DAS & ERRCS: ' . $firstUsableIp);
            }
        } elseif (strtolower($systemType) === 'errcs') {
            // For ERRCS only, use ERRCS BDA 1 IP
            if (!empty($ipAssignments)) {
                foreach ($ipAssignments as $assignment) {
                    if (strpos($assignment['label'], 'ERRCS BDA 1') !== false) {
                        $firstUsableIp = $assignment['ip'];
                        Log::info('Found ERRCS BDA 1 IP: ' . $firstUsableIp);
                        break;
                    }
                }
            }
            
            // If no IP assignment found in the array (e.g., quantities are 0), 
            // use the first usable IP from the IP range
            if ($firstUsableIp === null) {
                $firstUsableIp = ip_add($ipRow->first_usable_ip, 1);
                Log::info('Using fallback IP for ERRCS: ' . $firstUsableIp);
            }
        }
        
        Log::info('Final first_usable_ip: ' . $firstUsableIp);
        
        // Ensure first_usable_ip is never null
        if ($firstUsableIp === null) {
            Log::error('first_usable_ip is still null after all logic. Using fallback.');
            $firstUsableIp = ip_add($ipRow->first_usable_ip, 1);
        }

        // Update the NetworkManagement record with the generated data
        $networkManagement->update([
            'first_usable_ip' => $firstUsableIp,
            'xml_config_file' => $templateString,
            'random_password' => $randomPassword
        ]);

        // Store the data in session to prevent duplicate submissions
        session([$submissionKey => [
            'propertyName' => $validated['property_name'],
            'ipAssignments' => $ipAssignments,
            'xmlFile' => $xmlFileName,
            'randomPassword' => $randomPassword,
            'provisionId' => $provisionId
        ]]);

        // Return the view directly to keep user on /network-provisioning/store
        return view('network-provisioning.pfsense', [
            'propertyName' => $validated['property_name'],
            'ipAssignments' => $ipAssignments,
            'xmlFile' => $xmlFileName,
            'randomPassword' => $randomPassword,
            'provisionId'   => $provisionId
        ]);
    }



    public function downloadXml($fileName)
    {
        return \Illuminate\Support\Facades\Storage::disk('local')->download('xml/' . $fileName, $fileName);
    }

    /**
     * Download XML config file from database
     */
    public function downloadXmlFromDatabase($id)
    {
        try {
            $networkManagement = NetworkManagement::findOrFail($id);
            
            if (!$networkManagement->xml_config_file) {
                return redirect()->back()->with('error', 'No XML configuration file found for this record.');
            }

            // Build a safe base filename and then append the extension so the dot isn't sanitized away
            $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $networkManagement->property_name . '_config');
            $fileName = $baseName . '.xml';
            
            return response($networkManagement->xml_config_file)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
                
        } catch (\Exception $e) {
            Log::error('Error downloading XML config file: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Error downloading XML configuration file.');
        }
    }

    /**
     * Get all network management data for the provisioning table
     */
    public function getNetworkManagementData()
    {
        try {
            $data = NetworkManagement::select([
                'id',
                'property_name',
                'property_type', 
                'property_address',
                'system_type',
                'first_usable_ip',
                'xml_config_file',
                'created_at',
                'updated_at'
            ])->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching network management data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data'
            ], 500);
        }
    }

    /**
     * Show detailed view of a specific network management record
     */
    public function showDetails($id)
    {
        try {
            $networkManagement = NetworkManagement::findOrFail($id);
            
            return view('network-provisioning.details', [
                'networkManagement' => $networkManagement
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching network management details: ' . $e->getMessage());
            
            return redirect()->route('network-provisioning.search')
                ->with('error', 'Network management record not found.');
        }
    }
}