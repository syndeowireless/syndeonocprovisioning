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

        $property_name      = $provision -> property_name;
        $hostname           = $provision -> hostname;
        $static_ip          = $provision -> static_ip;
        $random_password    = $provision -> random_password;
        $first_usable_ip    = $provision -> first_usable_ip;
        $grafana_toggle     = $provision -> grafana_toggle;


        
        
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
        
        // Grafana: Add user
        $userResp = $this->grafanaApiRequest('post', '/admin/users', [
            'name' => 'Test Grafana',
            'email' => 'provisioned@example.com',
            'login' => 'provisioned',
            'password' => 'changeme123',
        ]);
        
        // Grafana: Add dashboard
        $dashboardResp = $this->grafanaApiRequest('post', '/dashboards/db', [
            'dashboard' => [
                'id' => null,
                'title' => 'Provisioned Dashboard',
                'panels' => [],
            ],
            'overwrite' => false,
        ]);
        

        //PFSENSE
        if ($static_ip === null) {
            $remote_gateway = $hostname;

        }
        else {
            $remote_gateway = $static_ip;
        }

        $phase1Payload = [
            "descr" => "$property_name", //property name
            "iketype" => "ikev2",                   
            "mode" => "main",                    
            "protocol" => "inet",                
            "interface" => "wan",                
            "remote_gateway" => "$remote_gateway",   //IP static senão host(dyndns)
            "authentication_method" => "pre_shared_key", 
            "pre_shared_key" => "$random_password", // senha aleatória
            "myid_type" => "myaddress",          
            "peerid_type" => "peeraddress",      
            "lifetime" => 28800,
            "rekey_time" => 28700,
            "reauth_time" => 0,
            "encryption" => [
              [
                "encryption_algorithm_name"=> "aes",
                "encryption_algorithm_keylen"=> 128,
                "hash_algorithm"=> "sha256",
                "dhgroup"=> 14
              ]
            ]
        ];

        $pfBaseUrl = 'https://10.200.1.10:8443/api/v2';
        $pfUser = 'nortongauss';
        $pfPass = 'ng321*';

        // 1. Cria o Phase 1
        $phase1Resp = Http::withBasicAuth($pfUser, $pfPass)
            ->withoutVerifying()
            ->post("$pfBaseUrl/vpn/ipsec/phase1", $phase1Payload);

        if (!$phase1Resp->successful()) {
            return response()->json(['success' => false, 'error' => $phase1Resp->body()], $phase1Resp->status());
        }

        $phase1Data = $phase1Resp->json();
        $ikeid = $phase1Data['data']['ikeid'] ?? null; // Pega o ID do Phase 1 criado

        if (!$ikeid) {
            return response()->json(['success' => false, 'error' => 'Failed to get ikeid from Phase 1 creation']);
        }

        $Ip_Plan = subtract_from_last_octet($first_usable_ip, 2);

        // 2. Cria o Phase 2.1
        $phase2_1Payload = [
            "ikeid" => $ikeid,                          
            "descr" => "$property_name", // Property name
            "mode" => "tunnel",                    
            "localid_type" => "lan",               
            "localid_address" => "", 
            "localid_netbits" => 24,
            "remoteid_type" => "network",          
            "remoteid_address" => "$Ip_Plan", // IP-Plan (tabela de ips) NETWORK
            "remoteid_netbits" => 24,
            "protocol" => "esp",                   
            "encryption_algorithm_option" => [
              [
                "name" => "aes",
                "keylen" => 128
              ]
            ],
            "hash_algorithm_option" => ["hmac_sha1"],
            "pfsgroup" => 14,
            "lifetime" => 3600
        ];

        $phase2_1Resp = Http::withBasicAuth($pfUser, $pfPass)
            ->withoutVerifying()
            ->post("$pfBaseUrl/vpn/ipsec/phase2", $phase2_1Payload);

        if (!$phase2_1Resp->successful()) {
            return response()->json(['success' => false, 'phase1' => $phase1Resp->json(), 'error' => $phase2_1Resp->body()], $phase2_1Resp->status());
        }
        // 2. Cria o Phase 2.2
            
        $phase2_2Payload = [
            "ikeid" => $ikeid,                           
            "descr" => "OpenVPN", // OpenVPN
            "mode" => "tunnel",                    
            "localid_type" => "network",               
            "localid_address" => "10.0.8.0/24", 
            "localid_netbits" => 24,
            "remoteid_type" => "network",          
            "remoteid_address" => "$Ip_Plan", // IP-Plan (tabela de ips) NETWORK
            "remoteid_netbits" => 24,
            "protocol" => "esp",                   
            "encryption_algorithm_option" => [
              [
                "name" => "aes",
                "keylen" => 128
              ]
            ],
            "hash_algorithm_option" => ["hmac_sha1"],
            "pfsgroup" => 14,
            "lifetime" => 3600
        ];
            
        $phase2_2Resp = Http::withBasicAuth($pfUser, $pfPass)
            ->withoutVerifying()
            ->post("$pfBaseUrl/vpn/ipsec/phase2", $phase2_2Payload);


        return response()->json([
            'success' => true,
            'pfsense_phase1' => $phase1Resp->json(),
            'pfsense_phase2_1' => $phase2_1Resp->json(),
            'pfsense_phase2_2' => $phase2_2Resp->json(),
        ]);
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


function subtract_from_last_octet($ip, $subtract = 2) {
    $parts = explode('.', $ip);
    if (count($parts) === 4) {
        $parts[3] = (int)$parts[3] - $subtract;
        if ($parts[3] < 0) $parts[3] = 0; // prevent negative octet
        return implode('.', $parts);
    }
    return $ip; // return original if not a valid IPv4
}



}