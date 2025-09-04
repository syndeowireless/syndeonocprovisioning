<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\NetworkManagement;


class ProvisionController extends Controller
{
    public function start(Request $request)
    {

    try{ 
        $provisionId = $request->input('provision_id');
        
        $provision = NetworkManagement::find($provisionId);
        
        if (!$provision) {
            return response()->json(['success' => false, 'error' => 'Provision not found']);
        }
        
        return response()->json(['success' => true]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            // 'trace' => $e->getTraceAsString(), // Uncomment for more debug info if needed
        ], 500);
    }



        //return response()->json(['success' => true]);


        //// Step 1: Login Zabbix
        //$zabbixToken = $this->zabbixLogin();
        //if (!$zabbixToken) return response()->json(['success' => false, 'error' => 'Zabbix login failed']);
        //    
        //// Step 2: Get Host Groups
        //$groups = $this->zabbixApiRequest('hostgroup.get', ['output' => 'extend'], $zabbixToken);
        //$firstGroup = $groups['result'][0] ?? null;
        //if (!$firstGroup) return response()->json(['success' => false, 'error' => 'No Zabbix host groups found']);
        //    
        //// Step 3: Create Host
        //$hostParams = [
        //    'host' => 'MyNewHost', // Make dynamic if needed
        //    'interfaces' => [[
        //        'type' => 1, 'main' => 1, 'useip' => 1, 'ip' => '127.0.0.1', 'dns' => '', 'port' => '10050'
        //    ]],
        //    'groups' => [[ 'groupid' => $firstGroup['groupid'] ]],
        //];
        //$createResp = $this->zabbixApiRequest('host.create', $hostParams, $zabbixToken);
//
        //// Grafana: Add user
        //$userResp = $this->grafanaApiRequest('post', '/admin/users', [
        //    'name' => 'Test Grafana',
        //    'email' => 'provisioned@example.com',
        //    'login' => 'provisioned',
        //    'password' => 'changeme123',
        //]);
        //
        //// Grafana: Add dashboard
        //$dashboardResp = $this->grafanaApiRequest('post', '/dashboards/db', [
        //    'dashboard' => [
        //        'id' => null,
        //        'title' => 'Provisioned Dashboard',
        //        'panels' => [],
        //    ],
        //    'overwrite' => false,
        //]);
//
        ////PFSENSE
        //        // dados de entrada (test)
        //$phase1Payload = [
        //      "descr"=> "VPN Site-to-Site Phase 1",
        //      "iketype"=> "ikev2",                // ou "ikev1", "auto"
        //      "mode"=> "main",                    // obrigatório se for IKEv1
        //      "protocol"=> "inet",                // "inet" (IPv4), "inet6" (IPv6)
        //      "interface"=> "wan",                // Interface do pfSense
        //      "remote_gateway"=> "200.200.200.2", // IP ou hostname do peer remoto
        //      "authentication_method"=> "pre_shared_key", // ou "eap-radius", etc.
        //      "pre_shared_key"=> "test",
        //      "myid_type"=> "myaddress",          // ou "fqdn", "user_fqdn", "address"
        //      "peerid_type"=> "peeraddress",      // ou "fqdn", "user_fqdn", "address"
        //      "lifetime"=> 28800,
        //      "rekey_time"=> 28700,
        //      "reauth_time"=> 0,
        //      "encryption"=> [
        //        [
        //          "encryption_algorithm_name"=> "aes",
        //          "encryption_algorithm_keylen"=> 128,
        //          "hash_algorithm"=> "sha256",
        //          "dhgroup"=> 14
        //        ] 
        //        ]  
        //];
//
        //$pfBaseUrl = 'https://10.200.1.10:8443/api/v2';
        //$pfUser = 'nortongauss';
        //$pfPass = 'ng321*';
//
        //// 1. Cria o Phase 1
        //$phase1Resp = Http::withBasicAuth($pfUser, $pfPass)
        //    ->withoutVerifying()
        //    ->post("$pfBaseUrl/vpn/ipsec/phase1", $phase1Payload);
//
        //if (!$phase1Resp->successful()) {
        //    return response()->json(['success' => false, 'error' => $phase1Resp->body()], $phase1Resp->status());
        //}
//
        //$phase1Data = $phase1Resp->json();
        //$ikeid = $phase1Data['data']['ikeid'] ?? null; // Pega o ID do Phase 1 criado
//
        //if (!$ikeid) {
        //    return response()->json(['success' => false, 'error' => 'Failed to get ikeid from Phase 1 creation']);
        //}
//
        //// 2. Cria o Phase 2
        //$phase2Payload = [
        //      "ikeid"=> $ikeid,                          // ID do Phase 1 ao qual o Phase 2 pertence
        //      "descr"=> "VPN Site-to-Site Phase 2",
        //      "mode"=> "tunnel",                    // "tunnel", "transport", etc.
        //      "localid_type"=> "lan",               // "lan", "network", "address", etc.
        //      "localid_address"=> "192.168.1.0",
        //      "localid_netbits"=> 24,
        //      "remoteid_type"=> "network",          // "network", "address", etc.
        //      "remoteid_address"=> "192.168.2.0",
        //      "remoteid_netbits"=> 24,
        //      "protocol"=> "esp",                   // "esp" ou "ah"
        //      "encryption_algorithm_option"=> [
        //        [
        //          "name"=> "aes",
        //          "keylen"=> 128
        //        ]
        //      ],
        //      "hash_algorithm_option"=> ["hmac_sha1"],
        //      "pfsgroup"=> 14,
        //      "lifetime"=> 3600
        //];
//
        //$phase2Resp = Http::withBasicAuth($pfUser, $pfPass)
        //    ->withoutVerifying()
        //    ->post("$pfBaseUrl/vpn/ipsec/phase2", $phase2Payload);
//
        //if (!$phase2Resp->successful()) {
        //    return response()->json(['success' => false, 'phase1' => $phase1Resp->json(), 'error' => $phase2Resp->body()], $phase2Resp->status());
        //}
//
        //return response()->json([
        //    'success' => true,
        //    'pfsense_phase1' => $phase1Resp->json(),
        //    'pfsense_phase2' => $phase2Resp->json(),
        //]);
//
//
//
        //return response()->json([
        //    'success' => true,
        //    'zabbix' => $createResp,
        //    'grafana_user' => $userResp,
        //    'grafana_dashboard' => $dashboardResp,
        //]);
    }

    private function zabbixApiRequest($method, $params, $auth = null)
    {
        $url = 'http://10.200.1.4/zabbix/api_jsonrpc.php'; // <---- CHANGE THIS
        $post = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => 1,
        ];
        if ($auth) $post['auth'] = $auth;

        $response = Http::post($url, $post);
        return $response->json();
    }

    private function zabbixLogin()
    {
        $user = 'support';      // <---- CHANGE IF NEEDED
        $password = 'syndeo@123'; // <---- CHANGE IF NEEDED
        $result = $this->zabbixApiRequest('user.login', [
            'user' => $user,
            'password' => $password,
        ]);
        return $result['result'] ?? null;
    }

    private function grafanaApiRequest($method, $endpoint, $data = [])
    {
        $url = 'https://dashboard.syndeonoc.com/api' . $endpoint; // Ensure /api prefix
        $username = 'support';
        $password = 'syndeo@123';
        
        $response = Http::withBasicAuth($username, $password)
            ->$method($url, $data);
        
        return $response->json();
    }
}