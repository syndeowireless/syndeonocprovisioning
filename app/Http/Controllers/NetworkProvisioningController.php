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
        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'oem' => 'nullable|string|max:255',
            'property_address' => 'nullable|string|max:255',
            'remote_unit_quantity' => 'nullable|integer',
            'master_unit_quantity' => 'nullable|integer',
            'bda_quantity' => 'nullable|integer',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'property_type' => 'nullable|string|max:255',
            'average_density' => 'nullable|string|max:255',
            'system_type' => 'nullable|string|max:255',
        ]);

        $networkManagement = NetworkManagement::create($validated);

        $ipRow = Ip::where('in_use', false)->first();
        if (!$ipRow) {
            return back()->with('error', 'No available IP ranges.');
        }
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
        $randomPassword = \Illuminate\Support\Str::random(12);

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
        
        if (strtolower($systemType) === 'das' || strtolower($systemType) === 'das and errcs') {
            // For DAS or DAS and ERRCS, use Master Unit Sector 1 IP
            if (!empty($ipAssignments)) {
                foreach ($ipAssignments as $assignment) {
                    if (strpos($assignment['label'], 'Master Unit Sector 1') !== false) {
                        $firstUsableIp = $assignment['ip'];
                        break;
                    }
                }
            }
        } elseif (strtolower($systemType) === 'errcs') {
            // For ERRCS only, use ERRCS BDA 1 IP
            if (!empty($ipAssignments)) {
                foreach ($ipAssignments as $assignment) {
                    if (strpos($assignment['label'], 'ERRCS BDA 1') !== false) {
                        $firstUsableIp = $assignment['ip'];
                        break;
                    }
                }
            }
        }

        // Update the NetworkManagement record with the generated data
        $networkManagement->update([
            'first_usable_ip' => $firstUsableIp,
            'xml_config_file' => $templateString
        ]);

        // Retorno para a view
        return view('network-provisioning.pfsense', [
            'propertyName' => $validated['property_name'],
            'ipAssignments' => $ipAssignments,
            'xmlFile' => $xmlFileName,
            'randomPassword' => $randomPassword
        ]);
    }

    public function downloadXml($fileName)
    {
        return \Illuminate\Support\Facades\Storage::disk('local')->download('xml/' . $fileName, $fileName);
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
                'first_usable_ip'
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