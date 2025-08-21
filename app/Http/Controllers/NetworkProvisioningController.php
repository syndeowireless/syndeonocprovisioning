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

        NetworkManagement::create($validated);

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
            $placeholders['#lan.ipaddr#'] = '';
        } else {
            $placeholders['#wan.ipaddr#'] = '';
            $placeholders['#wan.mask#']   = '';
            $placeholders['#lan.ipaddr#'] = $ipRows->first_usable_ip ?? '';
        }

        // Substituir todos os placeholders
        foreach ($placeholders as $key => $value) {
            $templateString = str_replace($key, $value, $templateString);
        }

        // Salvar arquivo XML
        $xmlFileName = 'config_file_' . $validated['property_name'] . '.xml';
        \Illuminate\Support\Facades\Storage::disk('local')->put('xml/' . $xmlFileName, $templateString);

        // Retorno para a view
        return view('network-provisioning.pfsense', [
            'propertyName' => $validated['property_name'],
            'ipData' => $ipData,
            'xmlFile' => $xmlFileName,
            'randomPassword' => $randomPassword
        ]);
    }

    public function downloadXml($fileName)
    {
        return \Illuminate\Support\Facades\Storage::disk('local')->download('xml/' . $fileName, $fileName);
    }
}