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


        \App\Models\NetworkManagement::create($validated);

        return redirect()->back()->with('success', 'Network Provisioning created!');
    }
}

