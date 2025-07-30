<?php

namespace App\Http\Controllers;

use App\Models\NetworkManagement;
use App\Models\Ip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NetworkProvisioningController extends Controller
{
    /**
     * Display the network provisioning form.
     */
    public function create()
    {
        return view('network-provisioning.create');
    }

    /**
     * Store a newly created network provisioning in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'property_name' => 'required|string|max:255',
            'oem' => 'nullable|string|max:100',
            'property_address' => 'nullable|string',
            'remote_unit_quantity' => 'nullable|integer|min:0',
            'master_unit_quantity' => 'nullable|integer|min:0',
            'bda_quantity' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'property_type' => 'nullable|string|in:Hotel,Factory,Office,Residencial,Others',
            'average_density' => 'nullable|string|in:Low,Medium,High',
            'system_type' => 'nullable|string|in:DAS,ERRCS,DAS & ERRCS',
        ]);

        try {
            DB::beginTransaction();

            // Find the first available IP range
            $availableIp = Ip::available()->first();

            if (!$availableIp) {
                return back()->withErrors(['error' => 'No IP ranges available. Please contact the administrator.']);
            }

            // Create the network management entry
            $validatedData['allocated_ip_id'] = $availableIp->id;
            $networkManagement = NetworkManagement::create($validatedData);

            // Mark the IP range as in use
            $availableIp->markAsInUse();

            DB::commit();

            // Redirect to the pfsense page with the allocated IP information
            return redirect()->route('network-provisioning.pfsense', [
                'network_id' => $networkManagement->id,
                'ip_id' => $availableIp->id
            ])->with('success', 'Network provisioning created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating network provisioning: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'An error occurred while creating the network provisioning. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Display the pfsense configuration page.
     */
    public function pfsense(Request $request)
    {
        $networkId = $request->get('network_id');
        $ipId = $request->get('ip_id');

        // Get the network management entry
        $networkManagement = NetworkManagement::findOrFail($networkId);
        
        // Get the allocated IP range
        $allocatedIp = Ip::findOrFail($ipId);

        return view('network-provisioning.pfsense', compact('networkManagement', 'allocatedIp'));
    }

    /**
     * Display all network provisioning entries with their allocated IPs.
     */
    public function index()
    {
        $networkProvisionings = NetworkManagement::with('allocatedIp')->get();
        $allocatedIps = Ip::inUse()->get();

        return view('network-provisioning.index', compact('networkProvisionings', 'allocatedIps'));
    }

    /**
     * Release an IP range (mark as available).
     */
    public function releaseIp($ipId)
    {
        try {
            $ip = Ip::findOrFail($ipId);
            $ip->markAsAvailable();

            return back()->with('success', 'IP range released successfully!');
        } catch (\Exception $e) {
            Log::error('Error releasing IP: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while releasing the IP range.']);
        }
    }
}

