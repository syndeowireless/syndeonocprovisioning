<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProvisionController extends Controller
{
    public function start(Request $request)
    {
        // Step 1: Login Zabbix
        $zabbixToken = $this->zabbixLogin();
        if (!$zabbixToken) return response()->json(['success' => false, 'error' => 'Zabbix login failed']);

        // Step 2: Get Host Groups
        $groups = $this->zabbixApiRequest('hostgroup.get', ['output' => 'extend'], $zabbixToken);
        $firstGroup = $groups['result'][0] ?? null;
        if (!$firstGroup) return response()->json(['success' => false, 'error' => 'No Zabbix host groups found']);

        // Step 3: Create Host
        $hostParams = [
            'host' => 'MyNewHost', // Make dynamic if needed
            'interfaces' => [[
                'type' => 1, 'main' => 1, 'useip' => 1, 'ip' => '127.0.0.1', 'dns' => '', 'port' => '10050'
            ]],
            'groups' => [[ 'groupid' => $firstGroup['groupid'] ]],
        ];
        $createResp = $this->zabbixApiRequest('host.create', $hostParams, $zabbixToken);

        // Grafana: Add user
        $userResp = $this->grafanaApiRequest('post', '/admin/users', [
            'name' => 'Provisioned User',
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

        return response()->json([
            'success' => true,
            'zabbix' => $createResp,
            'grafana_user' => $userResp,
            'grafana_dashboard' => $dashboardResp,
        ]);
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
        $url = 'https://dashboard.syndeonoc.com/' . $endpoint; // <---- CHANGE THIS
        $apiKey = env('GRAFANA_API_KEY');                    // <---- CHANGE THIS

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->$method($url, $data);

        return $response->json();
    }
}

