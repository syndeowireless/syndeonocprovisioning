<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\NetworkManagement;


class ProvisionController extends Controller
{
    public function start(Request $request)
    {
        $results = [];
        $errors = [];
    // try { 
        $provisionId = $request->input('provision_id');
        
        $provision = NetworkManagement::find($provisionId);
        if (!$provision) {
            return response()->json(['success' => false, 'error' => 'Provision not found'], 404);
        }

        $property_name          = $provision -> property_name;
        $hostname               = $provision -> hostname;
        $static_ip              = $provision -> static_ip;
        $random_password        = $provision -> random_password;
        $first_usable_ip        = $provision -> first_usable_ip;
        $grafana_toggle         = $provision -> grafana_toggle; 
        $company_name           = $provision -> company_name;
        $customer_email         = $provision -> customer_email;
        $system_type            = $provision -> system_type;
        $oem                    = $provision -> oem;
        $master_unit_quantity   = $provision -> master_unit_quantity;
        $bda_quantity           = $provision -> bda_quantity;



        
        
    //     if (!$provision) {
    //         return response()->json(['success' => false, 'error' => 'Provision not found']);
    //     }
        
    //     return response()->json(['success' => true]);
    // } catch (\Throwable $e) {
    //     return response()->json([
    //         'success' => false,
    //         'error' => $e->getMessage(),
    //         // 'trace' => $e->getTraceAsString(), // Uncomment for more debug info if needed
    //     ], 500);
    // }



        //return response()->json(['success' => true]);


        //Zabbix 
        //try {
        //    $auth = $this->zabbixLogin();
        //    if (!$auth) {
        //        throw new \Exception("Zabbix login failed");
        //    }
        //
        //    // 1. Ensure host group exists
        //    $groupId = $this->getOrCreateHostGroup($company_name, $auth);
        //
        //    // 2. Get template ID based on $oem
        //    $templateId = $this->getTemplateIdByName($oem, $auth);
        //
        //    // 3. Determine hosts to create
        //    $hosts = [];
        //    if ($system_type === 'DAS' || $system_type === 'ERRCS') {
        //        $hosts[] = $system_type;
        //    } elseif ($system_type === 'DAS & ERRCS') {
        //        $hosts[] = 'DAS';
        //        $hosts[] = 'ERRCS';
        //    }
        //
        //    //$createdHosts = [];
        //    //foreach ($hosts as $hostType) {
        //    //    $hostName = "{$company_name} {$hostType}";
        //    //    $result = $this->zabbixApiRequest('host.create', [
        //    //        'host' => $hostName,
        //    //        'groups' => [['groupid' => $groupId]],
        //    //        'templates' => [['templateid' => $templateId]],
        //    //        'interfaces' => [[
        //    //            'type' => 1,
        //    //            'main' => 1,
        //    //            'useip' => 1,
        //    //            'ip' => '127.0.0.1',  
        //    //            'dns' => '',
        //    //            'port' => '10050'
        //    //        ]],
        //    //    ], $auth);
        //    //
        //    //    $createdHosts[] = $result['result'] ?? $result['error'] ?? null;
        //    //}
//
//
        //    // Create hosts for master units
        //    for ($i = 1; $i <= $master_unit_quantity; $i++) {
        //        $hostName = "{$hostName} master unit {$i}";
        //        $result = $this->zabbixApiRequest('host.create', [
        //            'host' => $hostName,
        //            'groups' => [['groupid' => $groupId]],
        //            'templates' => [['templateid' => $templateId]],
        //            'interfaces' => [[
        //                'type' => 1,
        //                'main' => 1,
        //                'useip' => 1,
        //                'ip' => $currentIp,
        //                'dns' => '',
        //                'port' => '10050'
        //            ]],
        //        ], $auth);
        //        $createdHosts[] = $result;
        //        $currentIp = ipIncrement($currentIp, 1);
        //    }
//
        //    // Create hosts for BDAs
        //    for ($i = 1; $i <= $bda_quantity; $i++) {
        //        $hostName = "{$hostName} bda {$i}";
        //        $result = $this->zabbixApiRequest('host.create', [
        //            'host' => $hostName,
        //            'groups' => [['groupid' => $groupId]],
        //            'templates' => [['templateid' => $templateId]],
        //            'interfaces' => [[
        //                'type' => 1,
        //                'main' => 1,
        //                'useip' => 1,
        //                'ip' => $currentIp,
        //                'dns' => '',
        //                'port' => '10050'
        //            ]],
        //        ], $auth);
        //        $createdHosts[] = $result;
        //        $currentIp = ipIncrement($currentIp, 1);
        //    }
        //    $results['zabbix'] = 'Success';
        //} catch (\Throwable $e) {
        //    $errors['zabbix'] = $e->getMessage();
        //}

        // Grafana FUNCIONANDO E TESTADO
        //try {
        //    if ($grafana_toggle === null) {
        //        // Set your variables
        //        $folderUid = 'bedmyrwbic7pce';
//
        //        // Fetch the folder info to get its numeric ID
        //        $folderResp = $this->grafanaApiRequest('get', '/folders/' . $folderUid);
        //        $folderId = $folderResp['id'];
//
        //        if ('ADRF' === 'ADRF') {
        //            $templateUid1 = 'beiyn9fdbtvale';
        //        } elseif ('oem' === 'COMBA ERRCS') {
        //            $templateUid1 = 'beiyn9fdbt5hce';
        //        }
//
        //        if ('oem' === 'ADRF') {
        //            $templateUid2 = 'feutv2m5zcs1se';
        //        } elseif ('COMBA ERRCS' === 'COMBA ERRCS') {
        //            $templateUid2 = 'aebkeah3awdba';
        //        }
//
//
        //        // --------- Dashboard 1 ---------
        //        $templateResp1 = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUid1);
        //        $templateDashboard1 = $templateResp1['dashboard'];
//
        //        // Prepare the dashboard payload
        //        unset($templateDashboard1['id'], $templateDashboard1['uid']);
        //        $templateDashboard1['title'] = 'dashboard test 1';
//
        //        // Create dashboard 1 in the folder
        //        $newDashboardResp1 = $this->grafanaApiRequest('post', '/dashboards/db', [
        //            'dashboard' => $templateDashboard1,
        //            'folderId'  => $folderId,
        //            'overwrite' => false,
        //        ]);
//
        //        // --------- Dashboard 2 ---------
        //        $templateResp2 = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUid2);
        //        $templateDashboard2 = $templateResp2['dashboard'];
//
        //        unset($templateDashboard2['id'], $templateDashboard2['uid']);
        //        $templateDashboard2['title'] = 'Dashboard test 2';
//
        //        // Create dashboard 2 in the folder
        //        $newDashboardResp2 = $this->grafanaApiRequest('post', '/dashboards/db', [
        //            'dashboard' => $templateDashboard2,
        //            'folderId'  => $folderId,
        //            'overwrite' => false,
        //        ]);
//
//
//
        //    } else {
//
        //        // Grafana: Add dashboard
        //        $folderResp = $this->grafanaApiRequest('post', '/folders', [
        //            'title' => $company_name,
        //        ]);
        //        $folderId = $folderResp['id'] ?? null; 
        //    
        //        $modelUid = 'ceim11u2kzegwa'; // Affiliated Development Overview uid 
        //        $modelDashboardResp = $this->grafanaApiRequest('get', '/dashboards/uid/'.$modelUid);
        //        $modelDashboard = $modelDashboardResp['dashboard'];
        //        $modelDashboardId = $modelDashboard['id'];
        //    
        //    
        //        $newDashboard = $modelDashboard;
        //        unset($newDashboard['id']);
        //        unset($newDashboard['uid']);
        //        $newDashboard['title'] = $company_name;
        //    
        //    
        //        $dashboardResp = $this->grafanaApiRequest('post', '/dashboards/db', [
        //            'dashboard' => $newDashboard,
        //            'folderId'  => $folderId, 
        //            'overwrite' => false,
        //        ]);
        //    
        //        $email_parts = explode('@', $customer_email);
        //        $username_grafana = $email_parts[0];
        //        #####
        //        // 1. Create new user
        //        $newUserResp = $this->grafanaApiRequest('post', '/admin/users', [
        //            'name' => $username_grafana,
        //            'email' => $customer_email,
        //            'login' => $username_grafana,
        //            'password' => '$ynd30@noc',
        //        ]);
        //        $newUserId = $newUserResp['id'] ?? null;
//
        //        // 2. Get permissions for the model dashboard
        //        $dashboardId = $modelDashboardId; 
        //        $permissionsResp = $this->grafanaApiRequest('get', "/dashboards/id/{$dashboardId}/permissions");
        //        $permissions = $permissionsResp; 
//
        //        // 3. Find the model user's permission
        //        $modelUserId = $modelUserId; //ask Tayroni tomorrow witch user to get.
        //        $modelUserPermission = collect($permissions)->firstWhere('userId', $modelUserId);
//
        //        // 4. Assign the same permission to the new user
        //        if ($modelUserPermission && $newUserId) {
        //            $payload = [
        //                [
        //                    'userId' => $newUserId,
        //                    'permission' => $modelUserPermission['permission'],
        //                ]
        //            ];
        //            $setPermissionResp = $this->grafanaApiRequest('post', "/dashboards/id/{$dashboardId}/permissions", $payload);
        //        }
        //        #####
        //    }
        //    $results['grafana'] = 'Success';
        //} catch (\Throwable $e) {
        //    $errors['grafana'] = $e->getMessage();
        //}




        //PFSENSE
        try {
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

           $phase1Payload_JSON = json_encode($phase1Payload);

           $pfBaseUrl = 'https://10.200.1.10:8443/api/v2';
           $pfUser = 'nortongauss';
           $pfPass = 'ng321*';

           // 1. Cria o Phase 1
           $phase1Resp = Http::withBasicAuth($pfUser, $pfPass)
               ->withoutVerifying()
               ->post("$pfBaseUrl/vpn/ipsec/phase1", $phase1Payload_JSON);

           if (!$phase1Resp->successful()) {
               //return response()->json(['success' => false, 'error' => $phase1Resp->body()], $phase1Resp->status());
               throw new \Exception('Phase 1 creation failed: ' . $phase1Resp->body());
           }

           $phase1Data = $phase1Resp->json();
           $ikeid = $phase1Data['data']['ikeid'] ?? null; // Pega o ID do Phase 1 criado

           if (!$ikeid) {
               //return response()->json(['success' => false, 'error' => 'Failed to get ikeid from Phase 1 creation']);
               throw new \Exception('Failed to get ikeid from Phase 1 creation');
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

           $phase2_1Payload_JSON = json_encode($phase2_1Payload);

           $phase2_1Resp = Http::withBasicAuth($pfUser, $pfPass)
               ->withoutVerifying()
               ->post("$pfBaseUrl/vpn/ipsec/phase2", $phase2_1Payload_JSON);

           if (!$phase2_1Resp->successful()) {
               //return response()->json(['success' => false, 'phase1' => $phase1Resp->json(), 'error' => $phase2_1Resp->body()], $phase2_1Resp->status());
               throw new \Exception('Phase 2.1 creation failed: ' . $phase2_1Resp->body());
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

           $phase2_2Payload_JSON = json_encode($phase2_2Payload);

           $phase2_2Resp = Http::withBasicAuth($pfUser, $pfPass)
               ->withoutVerifying()
               ->post("$pfBaseUrl/vpn/ipsec/phase2", $phase2_2Payload_JSON);


           //return response()->json([
           //    'success' => true,
           //    'pfsense_phase1' => $phase1Resp->json(),
           //    'pfsense_phase2_1' => $phase2_1Resp->json(),
           //    'pfsense_phase2_2' => $phase2_2Resp->json(),
           //]);
           $results['pfsense'] = 'Success';
        } catch (\Throwable $e) {
           $errors['pfsense'] = $e->getMessage();
        }          
        
        
    return response()->json([
        'results' => $results,
        'errors'  => $errors,
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
        
        $contentType = $response->header('Content-Type') ?? '';
        if (strpos($contentType, 'application/json') === false) {
            throw new \Exception('Zabbix non-JSON response: ' . $response->body());
        }

        return $response->json();
    }

    private function zabbixLogin()
    {
        $user = 'support';     
        $password = 'syndeo@123'; 
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

        $contentType = $response->header('Content-Type') ?? '';
        if (strpos($contentType, 'application/json') === false) {
            throw new \Exception('Grafana non-JSON response: ' . $response->body());
        }
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

// Helper: Get or create host group
private function getOrCreateHostGroup($groupName, $auth)
{
    $result = $this->zabbixApiRequest('hostgroup.get', [
        'filter' => ['name' => [$groupName]]
    ], $auth);
    if (!empty($result['result'])) {
        return $result['result'][0]['groupid'];
    }
    $create = $this->zabbixApiRequest('hostgroup.create', [
        'name' => $groupName
    ], $auth);
    return $create['result']['groupids'][0];
}
// Helper: Get template ID by name
private function getTemplateIdByName($templateName, $auth)
{
    $result = $this->zabbixApiRequest('template.get', [
        'filter' => ['host' => [$templateName]]
    ], $auth);
    if (!empty($result['result'])) {
        return $result['result'][0]['templateid'];
    }
    throw new \Exception("Template {$templateName} not found.");
}

}