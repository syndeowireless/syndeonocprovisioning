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
        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'oem' => 'nullable|string|max:255',
            'property_address' => 'nullable|string|max:255',
            'remote_unit_quantity' => 'nullable|integer',
            'master_unit_quantity' => 'nullable|integer',
            'bda_quantity' => 'nullable|integer',
            'latitude' => 'nullable|numeric|between:-90,90', // Use numeric if your DB expects it, else string
            'longitude' => 'nullable|numeric|between:-180,180', // Use numeric if your DB expects it, else string
            'property_type' => 'nullable|string|max:255',
            'average_density' => 'nullable|string|max:255',
            'system_type' => 'nullable|string|max:255',
        ]);

        \App\Models\NetworkManagement::create($validated);

                // Fetch 4 available IPs
        $ipRows = Ip::where('in_use', false)->limit(4)->get();

        if ($ipRows->count() < 4) {
            return back()->with('error', 'Not enough available IP ranges.');
        }

        // Mark as used
        foreach ($ipRows as $row) {
            $row->in_use = true;
            $row->save();
        }

        // Assign each IP row to its role
        $ipData = [
            'master_unit_1' => $ipRows[0],
            'master_unit_2' => $ipRows[1],
            'master_unit_3' => $ipRows[2],
            'errcs'         => $ipRows[3],
        ];

        // === END: IP assignment logic ===

        // Show the pfsense result page, passing data
        return view('network-provisioning.pfsense', [
            'propertyName' => $validated['property_name'],
            'ipData' => $ipData,
        ]);

    }
}

