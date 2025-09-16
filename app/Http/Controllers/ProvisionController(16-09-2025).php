<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\NetworkManagement;

class ProvisionController extends Controller
{
    public function start(Request $request)
    {
        $results = [];
        $errors = [];

        $provisionId = $request->input('provision_id');
        
        // 1. Recuperar o registro NetworkManagement
        $provision = NetworkManagement::find($provisionId);
        if (!$provision) {
            Log::error("ProvisionController: Provision ID não encontrado.", ['provision_id' => $provisionId]);
            return response()->json(['success' => false, 'error' => 'Provision not found'], 404);
        }

        // 2. Extrair e validar os dados necessários do registro
        $property_name          = $provision->property_name;
        $hostname               = $provision->hostname;
        $static_ip              = $provision->static_ip;
        $random_password        = $provision->random_password;
        $first_usable_ip        = $provision->first_usable_ip;
        $grafana_toggle         = $provision->grafana_toggle; 
        $company_name           = $provision->company_name;
        $customer_email         = $provision->customer_email;
        $system_type            = $provision->system_type;
        $oem                    = $provision->oem;
        $master_unit_quantity   = $provision->master_unit_quantity;
        $bda_quantity           = $provision->bda_quantity;
        $das_equipment          = $provision->das_equipment;
        $errcs_equipment        = $provision->errcs_equipment;
        $property_type          = $provision->property_type;
        $latitude               = $provision->latitude;
        $longitude               = $provision->longitude;


        // Validação básica dos campos essenciais para a API do pfSense
        if (empty($property_name) || empty($random_password) || empty($first_usable_ip)) {
            Log::error("ProvisionController: Dados essenciais ausentes ou inválidos do registro NetworkManagement.", [
                'property_name' => $property_name,
                'random_password' => '***', // Não logar senhas
                'first_usable_ip' => $first_usable_ip
            ]);
            return response()->json(['success' => false, 'error' => 'Dados de provisionamento incompletos ou inválidos.'], 400);
        }

        // Determinar remote_gateway
        $remote_gateway = ($static_ip === null) ? $hostname : $static_ip;
        if (empty($remote_gateway)) {
             Log::error("ProvisionController: remote_gateway está vazio. static_ip: {$static_ip}, hostname: {$hostname}");
             return response()->json(['success' => false, 'error' => 'Gateway remoto não pode ser determinado.'], 400);
        }





        //Zabbix 
        try {
            $auth = $this->zabbixLogin();
            if (!$auth) {
                Log::error("ZabbixController: Falha no login do Zabbix.");
                throw new \Exception("Zabbix login failed");
            }
            Log::info("ZabbixController: Login realizado com sucesso no Zabbix.");

            // 1. Ensure host group exists
            $groupId = $this->getOrCreateHostGroup($oem, $auth);
            Log::info("ZabbixController: Grupo de hosts obtido/criado.", ['group_id' => $groupId]);

            // 2. Get template ID based on Equipment
            $templateId_Master_Unit_Equipment = $this->getTemplateIdByName($das_equipment, $auth);
            Log::info("ZabbixController: Template Master Unit encontrado.", [
                'das_equipment' => $das_equipment,
                'template_id' => $templateId_Master_Unit_Equipment
            ]);           

            $templateId_BDA_Equipment = $this->getTemplateIdByName($errcs_equipment, $auth);
            Log::info("ZabbixController: Template BDA encontrado.", [
                'errcs_equipment' => $errcs_equipment,
                'template_id' => $templateId_BDA_Equipment
            ]);

            // 3. Determine hosts to create
            $currentIp = $first_usable_ip;
            $hostNameBase = $property_name;

            // Create hosts for master units
            for ($i = 1; $i <= $master_unit_quantity; $i++) {
                $hostName = "{$hostNameBase} master unit {$i}";
                Log::info("ZabbixController: Criando host master unit.", [
                    'host' => $hostName,
                    'ip' => $currentIp,
                    'template_id' => $templateId_Master_Unit_Equipment
                ]);
                $result = $this->zabbixApiRequest('host.create', [
                    'host' => $hostName,
                    'groups' => [['groupid' => $groupId]],
                    'templates' => [['templateid' => $templateId_Master_Unit_Equipment]],
                    'interfaces' => [[
                        'type' => 2,
                        'main' => 1,
                        'useip' => 1,
                        'ip' => $currentIp,
                        'dns' => '',
                        'port' => '161',
                            'details' => [
                                'version' => 2,
                                'community' => 'public'
                            ]
                    ]],
                    'tags' => [
                        ['tag' => 'Site', 'value' => $hostNameBase]
                        // add more tags if needed
                    ],
                    'inventory_mode' => 1, // manual
                    'inventory' => [
                        'type' => $property_type,
                        'type_full' => 'DAS',
                        'location_lat' => $latitude,
                        'location_lon' => $longitude,
                        'vendor' => $oem,
                        'url_a' => $currentIp
                    ]
                ], $auth);
                Log::info("ZabbixController: Host master unit criado.", [
                    'host' => $hostName,
                    'result' => $result
                ]);
                $createdHosts[] = $result;
                $currentIp = $this->ipIncrement($currentIp, 1);
            }

            // Create hosts for BDAs
            for ($i = 1; $i <= $bda_quantity; $i++) {
                $hostName = "{$hostNameBase} bda {$i}";
                Log::info("ZabbixController: Criando host BDA.", [
                    'host' => $hostName,
                    'ip' => $currentIp,
                    'template_id' => $templateId_BDA_Equipment
                ]);
                $result = $this->zabbixApiRequest('host.create', [
                    'host' => $hostName,
                    'groups' => [['groupid' => $groupId]],
                    'templates' => [['templateid' => $templateId_BDA_Equipment]],
                    'interfaces' => [[
                        'type' => 2,
                        'main' => 1,
                        'useip' => 1,
                        'ip' => $currentIp,
                        'dns' => '',
                        'port' => '161',
                            'details' => [
                                'version' => 2,
                                'community' => 'public'
                            ]
                    ]],
                    'tags' => [
                        ['tag' => 'Site', 'value' => $hostNameBase]
                        // add more tags if needed
                    ],
                    'inventory_mode' => 1, // manual
                    'inventory' => [
                        'type' => $property_type,
                        'type_full' => 'ERRCS',
                        'location_lat' => $latitude,
                        'location_lon' => $longitude,
                        'vendor' => $oem,
                        'url_a' => $currentIp
                    ]

                ], $auth);
                Log::info("ZabbixController: Host BDA criado.", [
                    'host' => $hostName,
                    'result' => $result
                ]);
                $createdHosts[] = $result;
                $currentIp = $this->ipIncrement($currentIp, 1);
            }
            $results['zabbix'] = 'Success';
        } catch (\Throwable $e) {
            Log::error("ZabbixController: Exceção durante a chamada à API do Zabbix.", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $errors['zabbix'] = $e->getMessage();
        }







        // -------- INÍCIO LÓGICA GRAFANA --------
        try {
            // IDs de pasta e templates (preencher conforme sua necessidade)
            $syndeofolderUid = 'PASTE_FOLDER_UID_HERE';
            $syndeoFolderId = 'PASTE_FOLDER_ID_HERE';

            // Templates IDs para cada tipo (preencher conforme sua necessidade)
            $templateUidDas = 'PASTE_DAS_TEMPLATE_UID_HERE';
            $templateUidErrcs = 'PASTE_ERRCS_TEMPLATE_UID_HERE';
            $templateUidDasErrcs = 'PASTE_DAS_ERRCS_TEMPLATE_UID_HERE';

            // --- Função para gerar dashboards dinâmicos ---
            $buildDashboard = function($templateDashboard, $type, $qty, $oem, $property_name, $startIdx = 1) {
                $dashboards = [];
                for ($i = 0; $i < $qty; $i++) {
                    $idx = $i + $startIdx;
                    $name = "{$property_name}_{$idx}";
                    // Deep copy do template
                    $dashboard = json_decode(json_encode($templateDashboard), true);
                    $dashboard['title'] = $name;
                    // Substituir os placeholders em todo JSON
                    $dashboard = $this->substituteDashboardPlaceholders($dashboard, $oem, $name);
                    // Para cada panel, procurar targets com host.filter = PROPERTY_NAME e substituir pelo nome correto
                    if (isset($dashboard['panels']) && is_array($dashboard['panels'])) {
                        foreach ($dashboard['panels'] as &$panel) {
                            if (isset($panel['targets']) && is_array($panel['targets'])) {
                                foreach ($panel['targets'] as &$target) {
                                    if (isset($target['host']['filter']) && $target['host']['filter'] === 'PROPERTY_NAME') {
                                        $target['host']['filter'] = $name;
                                    }
                                }
                            }
                        }
                    }
                    $dashboards[] = $dashboard;
                }
                return $dashboards;
            };

            
            // --- Geração dinâmica de dashboards ---
            // 1. Se NÃO houver credencial do Grafana (grafana_toggle === null)
            if ($grafana_toggle === null) {
                // Buscar template correto pelo system_type
                $dashboardsToCreate = [];
                if ($system_type === 'DAS') {
                    $templateResp = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidDas);
                    $templateDashboard = $templateResp['dashboard'];
                    $dashboardsToCreate = $buildDashboard($templateDashboard, 'DAS', $master_unit_quantity, $oem, $property_name);
                } elseif ($system_type === 'ERRCS') {
                    $templateResp = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidErrcs);
                    $templateDashboard = $templateResp['dashboard'];
                    $dashboardsToCreate = $buildDashboard($templateDashboard, 'ERRCS', $bda_quantity, $oem, $property_name);
                } elseif ($system_type === 'DAS & ERRCS') {
                    // DAS
                    $templateRespDas = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidDas);
                    $templateDashboardDas = $templateRespDas['dashboard'];
                    $dasDashboards = $buildDashboard($templateDashboardDas, 'DAS', $master_unit_quantity, $oem, $property_name, 1);

                    // ERRCS (começa do master_unit_quantity+1)
                    $templateRespErrcs = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidErrcs);
                    $templateDashboardErrcs = $templateRespErrcs['dashboard'];
                    $errcsDashboards = $buildDashboard($templateDashboardErrcs, 'ERRCS', $bda_quantity, $oem, $property_name, $master_unit_quantity + 1);

                    $dashboardsToCreate = array_merge($dasDashboards, $errcsDashboards);
                }

                // Criar dashboards na pasta Syndeo
                foreach ($dashboardsToCreate as $dashboard) {
                    unset($dashboard['id'], $dashboard['uid']);
                    $resp = $this->grafanaApiRequest('post', '/dashboards/db', [
                        'dashboard' => $dashboard,
                        'folderId'  => $syndeoFolderId,
                        'overwrite' => false,
                    ]);
                    Log::info("Grafana: Dashboard criado na pasta Syndeo", ['response' => $resp]);
                }

                // --- SEGUNDO TEMPLATE conforme OEM ---
                if ($oem === 'ADRF') {
                    // PASTE SEGUNDO TEMPLATE UID PARA ADRF
                    $secondTemplateUid = 'PASTE_SECOND_TEMPLATE_UID_ADRF_HERE';
                    $templateResp2 = $this->grafanaApiRequest('get', '/dashboards/uid/' . $secondTemplateUid);
                    $templateDashboard2 = $templateResp2['dashboard'];
                    unset($templateDashboard2['id'], $templateDashboard2['uid']);
                    $templateDashboard2['title'] = $property_name . ' - ADRF';
                    $templateDashboard2 = $this->substituteDashboardPlaceholders($templateDashboard2, $oem, $property_name . ' - ADRF');
                    $resp2 = $this->grafanaApiRequest('post', '/dashboards/db', [
                        'dashboard' => $templateDashboard2,
                        'folderId'  => $syndeoFolderId,
                        'overwrite' => false,
                    ]);
                } elseif ($oem === 'COMBA' && $das_equipment === 'Syndeo V1.0 COMBA 202505 Model 2014') {
                    // PASTE SEGUNDO TEMPLATE UID PARA COMBA
                    $secondTemplateUid = 'PASTE_SECOND_TEMPLATE_UID_COMBA_HERE';
                    $templateResp2 = $this->grafanaApiRequest('get', '/dashboards/uid/' . $secondTemplateUid);
                    $templateDashboard2 = $templateResp2['dashboard'];
                    unset($templateDashboard2['id'], $templateDashboard2['uid']);
                    $templateDashboard2['title'] = $property_name . ' - COMBA';
                    $templateDashboard2 = $this->substituteDashboardPlaceholders($templateDashboard2, $oem, $property_name . ' - COMBA');
                    $resp2 = $this->grafanaApiRequest('post', '/dashboards/db', [
                        'dashboard' => $templateDashboard2,
                        'folderId'  => $syndeoFolderId,
                        'overwrite' => false,
                    ]);
                }
            }
            // 2. Se JÁ houver credencial do Grafana
            else {
                // Criar pasta Syndeo se necessário
                // (Pasta Syndeo já deve existir, apenas usar syndeoFolderId e syndeoFolderUid)

                // Seguir a mesma lógica para criar dashboards na pasta Syndeo
                $dashboardsToCreate = [];
                if ($system_type === 'DAS') {
                    $templateResp = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidDas);
                    $templateDashboard = $templateResp['dashboard'];
                    $dashboardsToCreate = $buildDashboard($templateDashboard, 'DAS', $master_unit_quantity, $oem, $property_name);
                } elseif ($system_type === 'ERRCS') {
                    $templateResp = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidErrcs);
                    $templateDashboard = $templateResp['dashboard'];
                    $dashboardsToCreate = $buildDashboard($templateDashboard, 'ERRCS', $bda_quantity, $oem, $property_name);
                } elseif ($system_type === 'DAS & ERRCS') {
                    $templateRespDas = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidDas);
                    $templateDashboardDas = $templateRespDas['dashboard'];
                    $dasDashboards = $buildDashboard($templateDashboardDas, 'DAS', $master_unit_quantity, $oem, $property_name, 1);

                    $templateRespErrcs = $this->grafanaApiRequest('get', '/dashboards/uid/' . $templateUidErrcs);
                    $templateDashboardErrcs = $templateRespErrcs['dashboard'];
                    $errcsDashboards = $buildDashboard($templateDashboardErrcs, 'ERRCS', $bda_quantity, $oem, $property_name, $master_unit_quantity + 1);

                    $dashboardsToCreate = array_merge($dasDashboards, $errcsDashboards);
                }
                foreach ($dashboardsToCreate as $dashboard) {
                    unset($dashboard['id'], $dashboard['uid']);
                    $resp = $this->grafanaApiRequest('post', '/dashboards/db', [
                        'dashboard' => $dashboard,
                        'folderId'  => $syndeoFolderId,
                        'overwrite' => false,
                    ]);
                }

                // --- SEGUNDO TEMPLATE conforme OEM ---
                if ($oem === 'ADRF') {
                    $secondTemplateUid = 'PASTE_SECOND_TEMPLATE_UID_ADRF_HERE';
                    $templateResp2 = $this->grafanaApiRequest('get', '/dashboards/uid/' . $secondTemplateUid);
                    $templateDashboard2 = $templateResp2['dashboard'];
                    unset($templateDashboard2['id'], $templateDashboard2['uid']);
                    $templateDashboard2['title'] = $property_name . ' - ADRF';
                    $templateDashboard2 = $this->substituteDashboardPlaceholders($templateDashboard2, $oem, $property_name . ' - ADRF');
                    $resp2 = $this->grafanaApiRequest('post', '/dashboards/db', [
                        'dashboard' => $templateDashboard2,
                        'folderId'  => $syndeoFolderId,
                        'overwrite' => false,
                    ]);
                } elseif ($oem === 'COMBA' && $das_equipment === 'Syndeo V1.0 COMBA 202505 Model 2014') {
                    $secondTemplateUid = 'PASTE_SECOND_TEMPLATE_UID_COMBA_HERE';
                    $templateResp2 = $this->grafanaApiRequest('get', '/dashboards/uid/' . $secondTemplateUid);
                    $templateDashboard2 = $templateResp2['dashboard'];
                    unset($templateDashboard2['id'], $templateDashboard2['uid']);
                    $templateDashboard2['title'] = $property_name . ' - COMBA';
                    $templateDashboard2 = $this->substituteDashboardPlaceholders($templateDashboard2, $oem, $property_name . ' - COMBA');
                    $resp2 = $this->grafanaApiRequest('post', '/dashboards/db', [
                        'dashboard' => $templateDashboard2,
                        'folderId'  => $syndeoFolderId,
                        'overwrite' => false,
                    ]);
                }

                // Criar nova pasta com nome da company
                $companyFolderResp = $this->grafanaApiRequest('post', '/folders', [
                    'title' => $company_name,
                ]);
                $companyFolderId = $companyFolderResp['id'] ?? null;

                // Criar dashboard na pasta da company baseado no system_type
                if ($companyFolderId) {
                    $companyTemplateUid = '';
                    if ($system_type === 'DAS') {
                        $companyTemplateUid = 'PASTE_DAS_TEMPLATE_UID_HERE';
                    } elseif ($system_type === 'ERRCS') {
                        $companyTemplateUid = 'PASTE_ERRCS_TEMPLATE_UID_HERE';
                    } elseif ($system_type === 'DAS & ERRCS') {
                        $companyTemplateUid = 'PASTE_DAS_ERRCS_TEMPLATE_UID_HERE';
                    }
                    if ($companyTemplateUid) {
                        $templateResp = $this->grafanaApiRequest('get', '/dashboards/uid/' . $companyTemplateUid);
                        $templateDashboard = $templateResp['dashboard'];
                        unset($templateDashboard['id'], $templateDashboard['uid']);
                        $templateDashboard['title'] = $company_name . ' - ' . $system_type;
                        $templateDashboard = $this->substituteDashboardPlaceholders($templateDashboard, $oem, $company_name . ' - ' . $system_type);
                        $resp = $this->grafanaApiRequest('post', '/dashboards/db', [
                            'dashboard' => $templateDashboard,
                            'folderId'  => $companyFolderId,
                            'overwrite' => false,
                        ]);
                    }
                }

                // Criar usuário e senha para o cliente
                $email_parts = explode('@', $customer_email);
                $username_grafana = $email_parts[0];
                $newUserResp = $this->grafanaApiRequest('post', '/admin/users', [
                    'name' => $username_grafana,
                    'email' => $customer_email,
                    'login' => $username_grafana,
                    'password' => 'PASTE_DEFAULT_PASSWORD_HERE',
                ]);
                Log::info("Grafana: Usuário criado", ['newUserResp' => $newUserResp]);
            }

            $results['grafana'] = 'Success';
            Log::info("Grafana: Provisionamento finalizado com sucesso", ['results' => $results]);
        } catch (\Throwable $e) {
            $errors['grafana'] = $e->getMessage();
            Log::error("Erro no provisionamento Grafana", [
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        // -------- FIM LÓGICA GRAFANA --------

        // Return a normalized JSON response for the frontend to redirect
        return response()->json([
            'success' => empty($errors),
            'provisioning_name' => $property_name,
            'errors' => $errors,
        ]);
    






        // Return a normalized JSON response for the frontend to redirect
        return response()->json([
            'success' => true,
            'provisioning_name' => $property_name,
        ]);


        // Início da chamada à API do pfSense
        // try {
        //     $phase1Payload = [
        //         "descr" => $property_name,
        //         "iketype" => "ikev2",
        //         "mode" => "main",
        //         "protocol" => "inet",
        //         "interface" => "wan",
        //         "remote_gateway" => $remote_gateway,
        //         "authentication_method" => "pre_shared_key",
        //         "pre_shared_key" => $random_password,
        //         "myid_type" => "keyid tag",
        //         "myid_data" => $property_name,
        //         "peerid_type" => "keyid tag",
        //         "peerid_data" => $remote_gateway,
        //         "lifetime" => 28800,
        //         "rekey_time" => 28700,
        //         "reauth_time" => 0,
        //         "encryption" => [
        //             [
        //                 "encryption_algorithm_name" => "aes",
        //                 "encryption_algorithm_keylen" => 128,
        //                 "hash_algorithm" => "sha256",
        //                 "dhgroup" => 14
        //             ]
        //         ]
        //     ];

            
        //     $pfBaseUrl = env('PFSENSE_API_BASE_URL', 'https://40.78.20.4:8443/api/v2'); // Usar variável de ambiente
        //     $pfApiKey = env('PFSENSE_API_KEY', '45029e5043a28667ecef6c198fb99b81'); // Usar variável de ambiente
        
        //     $httpClient = Http::withHeaders([
        //         'X-API-Key' => $pfApiKey,
        //         'Accept' => 'application/json'
        //     ])->withoutVerifying();

        //     // Log do payload antes de enviar
        //     Log::info("ProvisionController: Enviando Phase 1 Payload para pfSense API.", $phase1Payload);

        //     // 1. Cria o Phase 1
        //     $phase1Resp = $httpClient->post($pfBaseUrl . '/vpn/ipsec/phase1', $phase1Payload);
            
        //     if (!$phase1Resp->successful()) {
        //         Log::error("ProvisionController: Erro na API do pfSense (Phase 1).", [
        //             'status' => $phase1Resp->status(),
        //             'body' => $phase1Resp->body(),
        //             'payload_sent' => $phase1Payload // Logar o payload enviado em caso de erro
        //         ]);
        //         return response($phase1Resp->body(), $phase1Resp->status())
        //             ->header('Content-Type', $phase1Resp->header('Content-Type', 'text/html'));
        //     }
        //     // Log de sucesso Phase 1
        //     Log::info("ProvisionController: Phase 1 criado com sucesso no pfSense!", [
        //         'status' => $phase1Resp->status(),
        //         'body' => $phase1Resp->body(),
        //         'payload_enviado' => $phase1Payload
        //     ]);
        
        //     $phase1Data = $phase1Resp->json();
        //     if (!isset($phase1Data['data']['ikeid'])) {
        //         Log::error("ProvisionController: IKE ID não encontrado na resposta da Phase 1.", ['body' => $phase1Resp->body()]);
        //         return response($phase1Resp->body(), 500)
        //             ->header('Content-Type', $phase1Resp->header('Content-Type', 'text/html'));
        //     }
        
        //     $ikeid = $phase1Data['data']['ikeid'];
        
        //     // Chamar a função subtract_from_last_octet como método da classe
        //     $Ip_Plan = $this->subtract_from_last_octet($first_usable_ip, 2);
        //     if (empty($Ip_Plan)) {
        //         Log::error("ProvisionController: Ip_Plan está vazio após subtract_from_last_octet. first_usable_ip: {$first_usable_ip}");
        //         return response()->json(['success' => false, 'error' => 'Endereço IP para Phase 2 inválido.'], 400);
        //     }

        //     // 2. Cria o Phase 2.1
        //     $phase2_1Payload = [
        //         "ikeid" => $ikeid,
        //         "descr" => $property_name,
        //         "mode" => "tunnel",
        //         "localid_type" => "lan",
        //         "localid_address" => "10.0.2.0",
        //         "localid_netbits" => 24,
        //         "remoteid_type" => "network",
        //         "remoteid_address" => $Ip_Plan,
        //         "remoteid_netbits" => 24,
        //         "protocol" => "esp",
        //         "encryption_algorithm_option" => [
        //             [
        //                 "name" => "aes",
        //                 "keylen" => 128
        //             ]
        //         ],
        //         "hash_algorithm_option" => ["hmac_sha256"],
        //         "pfsgroup" => 14,
        //         "lifetime" => 3600
        //     ];

        //     Log::info("ProvisionController: Enviando Phase 2.1 Payload para pfSense API.", $phase2_1Payload);
        
        //     $phase2_1Resp = $httpClient->post($pfBaseUrl . '/vpn/ipsec/phase2', $phase2_1Payload);
        
        //     if (!$phase2_1Resp->successful()) {
        //         Log::error("ProvisionController: Erro na API do pfSense (Phase 2.1).", [
        //             'status' => $phase2_1Resp->status(),
        //             'body' => $phase2_1Resp->body(),
        //             'payload_sent' => $phase2_1Payload
        //         ]);
        //         return response($phase2_1Resp->body(), $phase2_1Resp->status())
        //             ->header('Content-Type', $phase2_1Resp->header('Content-Type', 'text/html'));
        //     }
        //     // Log de sucesso Phase 2.1
        //     Log::info("ProvisionController: Phase 2.1 criado com sucesso no pfSense!", [
        //         'status' => $phase2_1Resp->status(),
        //         'body' => $phase2_1Resp->body(),
        //         'payload_enviado' => $phase2_1Payload
        //     ]);
        
        //     // 3. Cria o Phase 2.2
        //     $phase2_2Payload = [
        //        "ikeid" => $ikeid,
        //        "descr" => "OpenVPN",
        //        "mode" => "tunnel",
        //        "localid_type" => "network",
        //        "localid_address" => "10.0.8.0",
        //        "localid_netbits" => 24,
        //        "remoteid_type" => "network",
        //        "remoteid_address" => $Ip_Plan,
        //        "remoteid_netbits" => 24,
        //        "protocol" => "esp",
        //        "encryption_algorithm_option" => [
        //            [
        //                "name" => "aes",
        //                "keylen" => 128
        //            ]
        //        ],
        //        "hash_algorithm_option" => ["hmac_sha256"],
        //        "pfsgroup" => 14,
        //        "lifetime" => 3600
        //     ];
            
        //     Log::info("ProvisionController: Enviando Phase 2.2 Payload para pfSense API.", $phase2_2Payload);
        
        //     $phase2_2Resp = $httpClient->post($pfBaseUrl . '/vpn/ipsec/phase2', $phase2_2Payload);
        
        //     if (!$phase2_2Resp->successful()) {
        //        Log::error("ProvisionController: Erro na API do pfSense (Phase 2.2).", [
        //            'status' => $phase2_2Resp->status(),
        //            'body' => $phase2_2Resp->body(),
        //            'payload_sent' => $phase2_2Payload
        //        ]);
        //        return response($phase2_2Resp->body(), $phase2_2Resp->status())
        //            ->header('Content-Type', $phase2_2Resp->header('Content-Type', 'text/html'));
        //     }
        //     // Log de sucesso Phase 2.2
        //     Log::info("ProvisionController: Phase 2.2 criado com sucesso no pfSense!", [
        //         'status' => $phase2_2Resp->status(),
        //         'body' => $phase2_2Resp->body(),
        //         'payload_enviado' => $phase2_2Payload
        //     ]);
        
        //     // If all went well, return JSON
        //     return response()->json(['success' => true, 'pfsense' => 'Success']);
        
        // } catch (\Throwable $e) {
        //     Log::error("ProvisionController: Exceção durante a chamada à API do pfSense.", [
        //         'message' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString()
        //     ]);
        //     return response($e->getMessage(), 500)
        //         ->header('Content-Type', 'text/plain');
        // }
    }


function substituteDashboardPlaceholders($data, $oem, $property_name) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = substituteDashboardPlaceholders($value, $oem, $property_name);
        }
        return $data;
    } elseif (is_string($data)) {
        // Replace both OEM and PROPERTY_NAME
        return str_replace(
            ['OEM', 'PROPERTY_NAME'],
            [$oem, $property_name],
            $data
        );
    } else {
        return $data;
    }
}




    private function ipIncrement($ip, $increment = 1)
    {
        $ipLong = ip2long($ip);
        if ($ipLong === false) {
            Log::warning("ProvisionController: IP inválido passado para ipIncrement: {$ip}");
            return $ip;
        }
        $newIp = long2ip($ipLong + $increment);
        return $newIp;
    }
    // Função auxiliar para subtrair do último octeto do IP
    private function subtract_from_last_octet($ip_address, $subtract_value)
    {
        if (filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            Log::warning("ProvisionController: IP inválido passado para subtract_from_last_octet: {$ip_address}");
            return null; // Retorna null para indicar IP inválido
        }

        $parts = explode('.', $ip_address);
        $last_octet = (int)end($parts);
        $parts[count($parts) - 1] = $last_octet - $subtract_value;
        return implode('.', $parts);
    }
    private function zabbixApiRequest($method, $params, $auth = null)
    {
        $url = 'http://40.78.20.4:8080/zabbix/api_jsonrpc.php';
        $post = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => 1,
        ];
        if ($auth) $post['auth'] = $auth;

        $response = Http::asJson()->post($url, $post);
        $json = $response->json();

        if (isset($json['error'])) {
            \Log::error('Zabbix API error', ['error' => $json['error']]);
            throw new \Exception("Zabbix API error: " . $json['error']['message'] . " (" . $json['error']['data'] . ")");
        }
        if (!isset($json['result'])) {
            throw new \Exception("Zabbix API: resposta inesperada: " . json_encode($json));
        }
        return $json['result'];
    }

    private function zabbixLogin()
    {
        $user = 'support';     
        $password = 'syndeo@123'; 
        $result = $this->zabbixApiRequest('user.login', [
            'username' => $user,
            'password' => $password,
        ]);
        \Log::error('Zabbix login response', ['result' => $result]);
        return $result;
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


// Helper: Get or create host group
    private function getOrCreateHostGroup($groupName, $auth)
    {
        // Zabbix espera string em filter.name, NÃO array!
        $result = $this->zabbixApiRequest('hostgroup.get', [
        'filter' => [
            'name' => $groupName
        ]
        ], $auth);
        if (!empty($result)) {
            return $result[0]['groupid'];
        }
        $create = $this->zabbixApiRequest('hostgroup.create', [
            'name' => $groupName
        ], $auth);
        return $create['groupids'][0];
    }
// Helper: Get template ID by name
private function getTemplateIdByName($templateName, $auth)
{
    // Listar todos os templates para debug
    //$allTemplates = $this->zabbixApiRequest('template.get', [
    //    'output' => ['templateid', 'host', 'name']
    //], $auth);
    //\Log::info('Lista de templates disponíveis', ['templates' => $allTemplates]);
//
    //// Buscar pelo filtro
    //$result = $this->zabbixApiRequest('template.get', [
    //    'filter' => [
    //        'host' => $templateName,
    //        'name' => $templateName
    //    ]
    //], $auth);
    //if (!empty($result)) {
    //    return $result[0]['templateid'];
    //}
    //throw new \Exception("Template {$templateName} not found.");
    // Normalize templateName: collapse multiple spaces, trim edges
    // Não normaliza para search!
    \Log::info('Buscando template pelo nome exato no search', [
        'original' => $templateName
    ]);
    $result = $this->zabbixApiRequest('template.get', [
        'search' => ['host' => $templateName],
        'output' => ['templateid', 'host', 'name']
    ], $auth);

    if (!empty($result)) {
        \Log::info('Templates encontrados pelo search', [
            'busca' => $templateName,
            'matches' => $result
        ]);
        foreach ($result as $tpl) {
            if ($tpl['host'] === $templateName) {
                \Log::info('Match exato encontrado para o template', [
                    'host' => $tpl['host'],
                    'templateid' => $tpl['templateid']
                ]);
                return $tpl['templateid'];
            }
        }
        return $result[0]['templateid'];
    }

    throw new \Exception("Template '{$templateName}' not found in Zabbix.");
    
}
}