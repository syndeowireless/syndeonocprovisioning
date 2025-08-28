<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProvisionController extends Controller
{
    public function start(Request $request)
    {
        // Here you will add the Zabbix and Grafana API logic in follow-up steps.
        // For now, just return a placeholder response.


        // Step 1: Login
        $zabbixToken = $this->zabbixLogin();
        if (!$zabbixToken) return response()->json(['success' => false, 'error' => 'Zabbix login failed']);

        // Step 2: Get Host Groups
        $groups = $this->zabbixApiRequest('hostgroup.get', ['output' => 'extend'], $zabbixToken);
        $firstGroup = $groups['result'][0] ?? null;
        if (!$firstGroup) return response()->json(['success' => false, 'error' => 'No Zabbix host groups found']);

        // Step 3: Create Host
        $hostParams = [
            'host' => 'MyNewHost', // You can make this dynamic
            'interfaces' => [[
                'type' => 1, 'main' => 1, 'useip' => 1, 'ip' => '127.0.0.1', 'dns' => '', 'port' => '10050'
            ]],
            'groups' => [[ 'groupid' => $firstGroup['groupid'] ]],
        ];
        $createResp = $this->zabbixApiRequest('host.create', $hostParams, $zabbixToken);

        //Grafana logic here...
        // Add user
        $userResp = $this->grafanaApiRequest('post', '/admin/users', [
            'name' => 'Provisioned User',
            'email' => 'provisioned@example.com',
            'login' => 'provisioned',
            'password' => 'changeme123',
        ]);
        
        // Add dashboard (simple example)
        $dashboardResp = $this->grafanaApiRequest('post', '/dashboards/db', [
            'dashboard' => [
                'id' => null,
                'title' => 'Provisioned Dashboard',
                'panels' => [],
            ],
            'overwrite' => false,
        ]);





        return response()->json(['success' => true, 'zabbix' => $createResp]);
    }

    private function zabbixApiRequest($method, $params, $auth = null)
    {
        $url = 'http://your-zabbix-server/api_jsonrpc.php'; // Update this!
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
        $user = 'your_zabbix_user';
        $password = 'your_zabbix_password';
        $result = $this->zabbixApiRequest('user.login', [
            'user' => $user,
            'password' => $password,
        ]);
        return $result['result'] ?? null;
    }

    private function grafanaApiRequest($method, $endpoint, $data = [])
    {
        $url = 'http://your-grafana-server/api' . $endpoint; // Update this!
        $apiKey = 'your_grafana_api_key';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->$method($url, $data);

        return $response->json();
    }
}