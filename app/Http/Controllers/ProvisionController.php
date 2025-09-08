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
        $property_name = $provision->property_name;
        $hostname = $provision->hostname;
        $static_ip = $provision->static_ip;
        $random_password = $provision->random_password;
        $first_usable_ip = $provision->first_usable_ip;
        $grafana_toggle = $provision->grafana_toggle; 
        $company_name = $provision->company_name;
        $customer_email = $provision->customer_email;
        $system_type = $provision->system_type;
        $oem = $provision->oem;
        $master_unit_quantity = $provision->master_unit_quantity;
        $bda_quantity = $provision->bda_quantity;

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

        // Início da chamada à API do pfSense
        try {
            $phase1Payload = [
                "descr" => $property_name,
                "iketype" => "ikev2",
                "mode" => "main",
                "protocol" => "inet",
                "interface" => "wan",
                "remote_gateway" => $remote_gateway,
                "authentication_method" => "pre_shared_key",
                "pre_shared_key" => $random_password,
                "myid_type" => "myaddress",
                "peerid_type" => "peeraddress",
                "lifetime" => 28800,
                "rekey_time" => 28700,
                "reauth_time" => 0,
                "encryption" => [
                    [
                        "encryption_algorithm_name" => "aes",
                        "encryption_algorithm_keylen" => 128,
                        "hash_algorithm" => "sha256",
                        "dhgroup" => 14
                    ]
                ]
            ];
        
            $pfBaseUrl = env('PFSENSE_API_BASE_URL', 'https://40.78.20.4:8443/api/v2'); // Usar variável de ambiente
            $pfApiKey = env('PFSENSE_API_KEY', '45029e5043a28667ecef6c198fb99b81'); // Usar variável de ambiente
        
            $httpClient = Http::withHeaders([
                'X-API-Key' => $pfApiKey,
                'Accept' => 'application/json'
            ])->withoutVerifying();

            // ----------- CHECAGEM DUPLICIDADE PHASE 1 -----------
            $existingPhase1Resp = $httpClient->get($pfBaseUrl . "/vpn/ipsec/phase1");
            if ($existingPhase1Resp->successful()) {
                $phase1DataList = $existingPhase1Resp->json();
                if (isset($phase1DataList['data']) && is_array($phase1DataList['data'])) {
                    foreach ($phase1DataList['data'] as $phase1) {
                        if ($phase1['remote_gateway'] === $phase1Payload['remote_gateway']) {
                            Log::error("ProvisionController: Phase 1 já existe para esse remote_gateway.", [
                                'remote_gateway' => $phase1Payload['remote_gateway']
                            ]);
                            return response()->json([
                                'success' => false,
                                'error' => 'Phase 1 já existe com esse remote_gateway.'
                            ], 400);
                        }
                    }
                }
            }
            // ----------- FIM CHECAGEM DUPLICIDADE PHASE 1 -----------

            // Log do payload antes de enviar
            Log::info("ProvisionController: Enviando Phase 1 Payload para pfSense API.", $phase1Payload);

            // 1. Cria o Phase 1
            $phase1Resp = $httpClient->post($pfBaseUrl . '/vpn/ipsec/phase1', $phase1Payload);
            
            if (!$phase1Resp->successful()) {
                Log::error("ProvisionController: Erro na API do pfSense (Phase 1).", [
                    'status' => $phase1Resp->status(),
                    'body' => $phase1Resp->body(),
                    'payload_sent' => $phase1Payload // Logar o payload enviado em caso de erro
                ]);
                return response($phase1Resp->body(), $phase1Resp->status())
                    ->header('Content-Type', $phase1Resp->header('Content-Type', 'text/html'));
            }
            // Log de sucesso Phase 1
            Log::info("ProvisionController: Phase 1 criado com sucesso no pfSense!", [
                'status' => $phase1Resp->status(),
                'body' => $phase1Resp->body(),
                'payload_enviado' => $phase1Payload
            ]);
        
            $phase1Data = $phase1Resp->json();
            if (!isset($phase1Data['data']['ikeid'])) {
                Log::error("ProvisionController: IKE ID não encontrado na resposta da Phase 1.", ['body' => $phase1Resp->body()]);
                return response($phase1Resp->body(), 500)
                    ->header('Content-Type', $phase1Resp->header('Content-Type', 'text/html'));
            }
        
            $ikeid = $phase1Data['data']['ikeid'];
        
            // Chamar a função subtract_from_last_octet como método da classe
            $Ip_Plan = $this->subtract_from_last_octet($first_usable_ip, 2);
            if (empty($Ip_Plan)) {
                Log::error("ProvisionController: Ip_Plan está vazio após subtract_from_last_octet. first_usable_ip: {$first_usable_ip}");
                return response()->json(['success' => false, 'error' => 'Endereço IP para Phase 2 inválido.'], 400);
            }

            // 2. Cria o Phase 2.1
            $phase2_1Payload = [
                "ikeid" => $ikeid,
                "descr" => $property_name,
                "mode" => "tunnel",
                "localid_type" => "lan",
                "localid_address" => "10.0.2.0",
                "localid_netbits" => 24,
                "remoteid_type" => "network",
                "remoteid_address" => $Ip_Plan,
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

            Log::info("ProvisionController: Enviando Phase 2.1 Payload para pfSense API.", $phase2_1Payload);
        
            $phase2_1Resp = $httpClient->post($pfBaseUrl . '/vpn/ipsec/phase2', $phase2_1Payload);
        
            if (!$phase2_1Resp->successful()) {
                Log::error("ProvisionController: Erro na API do pfSense (Phase 2.1).", [
                    'status' => $phase2_1Resp->status(),
                    'body' => $phase2_1Resp->body(),
                    'payload_sent' => $phase2_1Payload
                ]);
                return response($phase2_1Resp->body(), $phase2_1Resp->status())
                    ->header('Content-Type', $phase2_1Resp->header('Content-Type', 'text/html'));
            }
            // Log de sucesso Phase 2.1
            Log::info("ProvisionController: Phase 2.1 criado com sucesso no pfSense!", [
                'status' => $phase2_1Resp->status(),
                'body' => $phase2_1Resp->body(),
                'payload_enviado' => $phase2_1Payload
            ]);
        
            // 3. Cria o Phase 2.2
            $phase2_2Payload = [
               "ikeid" => $ikeid,
               "descr" => "OpenVPN",
               "mode" => "tunnel",
               "localid_type" => "network",
               "localid_address" => "10.0.2.1",
               "localid_netbits" => 24,
               "remoteid_type" => "network",
               "remoteid_address" => $Ip_Plan,
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

            // ----------- CHECAGEM DUPLICIDADE PHASE 2.2 -----------
            $existingPhase2Resp = $httpClient->get($pfBaseUrl . "/vpn/ipsec/phase2?ikeid={$ikeid}");
            if ($existingPhase2Resp->successful()) {
                $existingPhase2Data = $existingPhase2Resp->json();
                if (isset($existingPhase2Data['data']) && is_array($existingPhase2Data['data'])) {
                    foreach ($existingPhase2Data['data'] as $phase2) {
                        if (
                            $phase2['localid_address'] === $phase2_2Payload['localid_address'] &&
                            (int)$phase2['localid_netbits'] === (int)$phase2_2Payload['localid_netbits'] &&
                            $phase2['remoteid_address'] === $phase2_2Payload['remoteid_address'] &&
                            (int)$phase2['remoteid_netbits'] === (int)$phase2_2Payload['remoteid_netbits']
                        ) {
                            Log::error("ProvisionController: Phase 2.2 já existente para esse Phase 1.", [
                                'ikeid' => $ikeid,
                                'payload' => $phase2_2Payload
                            ]);
                            return response()->json([
                                'success' => false,
                                'error' => 'Phase 2.2 já existe para esse Phase 1 com esta combinação de redes.'
                            ], 400);
                        }
                    }
                }
            } else {
                Log::warning("ProvisionController: Não foi possível checar duplicidade de Phase 2.2.", [
                    'ikeid' => $ikeid,
                    'response' => $existingPhase2Resp->body()
                ]);
                // Você pode decidir se quer abortar aqui ou tentar criar mesmo assim.
            }
            // ----------- FIM CHECAGEM DUPLICIDADE PHASE 2.2 -----------
            
            Log::info("ProvisionController: Enviando Phase 2.2 Payload para pfSense API.", $phase2_2Payload);
        
            $phase2_2Resp = $httpClient->post($pfBaseUrl . '/vpn/ipsec/phase2', $phase2_2Payload);
        
            if (!$phase2_2Resp->successful()) {
               Log::error("ProvisionController: Erro na API do pfSense (Phase 2.2).", [
                   'status' => $phase2_2Resp->status(),
                   'body' => $phase2_2Resp->body(),
                   'payload_sent' => $phase2_2Payload
               ]);
               return response($phase2_2Resp->body(), $phase2_2Resp->status())
                   ->header('Content-Type', $phase2_2Resp->header('Content-Type', 'text/html'));
            }
            // Log de sucesso Phase 2.2
            Log::info("ProvisionController: Phase 2.2 criado com sucesso no pfSense!", [
                'status' => $phase2_2Resp->status(),
                'body' => $phase2_2Resp->body(),
                'payload_enviado' => $phase2_2Payload
            ]);
        
            // If all went well, return JSON
            return response()->json(['success' => true, 'pfsense' => 'Success']);
        
        } catch (\Throwable $e) {
            Log::error("ProvisionController: Exceção durante a chamada à API do pfSense.", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response($e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
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
}