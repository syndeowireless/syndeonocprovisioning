@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
@if(session("success"))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session("success") }}
    </div>
@endif

@extends("layouts.app")

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section("content")
<style>
    .switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0;
  right: 0; bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

.switch input:checked + .slider {
  background-color: #2196F3;
}

.switch input:checked + .slider:before {
  transform: translateX(20px);
}

/* Enhanced Form Styles */
.form-container {
    background: #f9fafb !important;
    min-height: calc(100vh-80px) !important;
    padding: 1rem 0 !important;
}

.form-wrapper {
    background: white !important;
    border-radius: 16px !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    padding: 2rem !important;
    border: 1px solid #e5e7eb !important;
}

.form-title {
    padding-top: 0.5rem !important;
    padding-bottom: 1rem !important;
    font-size: 1.5rem !important;
    font-family: 'Poppins', sans-serif !important;
    font-weight: 700 !important; /* Bold */
    color: #2B2B22 !important; /* Your specified color */
    margin-bottom: 1.5rem !important;
}

.form-group {
    margin-bottom: 0.75rem !important;
}

.form-label {
    display: block !important;
    color: #374151 !important;
    font-weight: 500 !important;
    margin-bottom: 0.375rem !important;
    font-size: 0.875rem !important;
}

/* STANDARDIZED INPUT SIZES - All inputs will have exact same dimensions */
.form-input, .form-select {
    width: 100% !important;
    height: 42px !important;
    padding: 0.625rem !important;
    border: 1px solid #d1d5db !important;
    border-radius: 6px !important;
    font-size: 0.875rem !important;
    line-height: 1.25rem !important;
    transition: all 0.2s ease !important;
    background: white !important;
    color: #374151 !important;
    box-sizing: border-box !important;
    vertical-align: top !important;
}

.form-input:focus, .form-select:focus {
    outline: none !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
}

.form-input::placeholder {
    color: #9ca3af !important;
    font-weight: 400 !important;
}

/* Ensure select elements match input height exactly */
.form-select {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
    background-position: right 0.5rem center !important;
    background-repeat: no-repeat !important;
    background-size: 1.5em 1.5em !important;
    padding-right: 2.5rem !important;
}

/* Readonly inputs should maintain same size */
.form-input[readonly] {
    background-color: #f9fafb !important;
    cursor: not-allowed !important;
}

.submit-button {
    background: #13395d !important;
    color: white !important;
    border: 2px solid #fbbf0f !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
    transition: all 0.2s ease !important;
    margin-top: 1rem !important;
    margin-bottom: 1rem !important;
    min-width: 120px !important;
}

.submit-button:hover {
    background: #FBBF0F !important;
    border:2px solid #13395D !important;
    transform: translateY(-1px) !important;
    color: #000 !important;
}

.grid-container {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 1rem !important;
    margin-bottom: 0.5rem !important;
}

.map-container {
    grid-column: 1 / -1 !important;
    margin-top: 0.5rem !important;
}

/* Switch container alignment to match input height */
.switch-container {
    display: flex !important;
    align-items: center !important;
    height: 42px !important;
    padding-top: 0 !important;
}

@media (max-width: 768px) {
    .grid-container {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .form-wrapper {
        padding: 1.5rem !important;
        border-radius: 12px !important;
    }
    
    .form-title {
        font-size: 1.375rem !important;
    }
}
</style>

<style>
    /* Estilos para as sugestões de endereço */
    .address-input-container {
        position: relative;
        margin-bottom: 0.5rem !important;
    }
    
    #address_suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 9999 !important;
        max-height: 200px;
        overflow-y: auto;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid #e5e7eb !important;
        background-color: white !important;
        margin-top: 4px !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
    }
    
    .address-suggestion {
        padding: 12px 16px !important;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9 !important;
        background-color: white !important;
        transition: all 0.2s ease !important;
        font-size: 0.875rem !important;
        line-height: 1.4 !important;
        color: #374151 !important;
        font-weight: 400 !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    .address-suggestion::before {
        content: '' !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
        width: 3px !important;
        background: #3b82f6 !important;
        transform: scaleY(0) !important;
        transition: transform 0.2s ease !important;
    }
    
    .address-suggestion:hover::before {
        transform: scaleY(1) !important;
    }
    
    /* Estado de carregamento para as sugestões */
    #address_suggestions.loading {
        position: relative !important;
    }
    
    #address_suggestions.loading::after {
        content: '' !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        width: 20px !important;
        height: 20px !important;
        margin: -10px 0 0 -10px !important;
        border: 2px solid #e5e7eb !important;
        border-top: 2px solid #3b82f6 !important;
        border-radius: 50% !important;
        animation: spin 1s linear infinite !important;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg) !important; }
        100% { transform: rotate(360deg) !important; }
    }
    
    /* Melhorar a aparência quando não há sugestões */
    #address_suggestions:empty::before {
        content: 'No addresses found' !important;
        display: block !important;
        padding: 16px !important;
        text-align: center !important;
        color: #6b7280 !important;
        font-style: italic !important;
    }
    
    .address-suggestion:hover {
        background-color: #f8fafc !important;
        color: #1f2937 !important;
        font-weight: 500 !important;
        transform: translateX(2px) !important;
    }
    
    .address-suggestion:last-child {
        border-bottom: none !important;
        border-radius: 0 0 8px 8px !important;
    }
    
    .address-suggestion:first-child {
        border-radius: 8px 8px 0 0 !important;
    }
    
    /* Estilização da scrollbar para as sugestões */
    #address_suggestions::-webkit-scrollbar {
        width: 6px !important;
    }
    
    #address_suggestions::-webkit-scrollbar-track {
        background: #f1f5f9 !important;
        border-radius: 3px !important;
    }
    
    #address_suggestions::-webkit-scrollbar-thumb {
        background: #cbd5e1 !important;
        border-radius: 3px !important;
    }
    
    #address_suggestions::-webkit-scrollbar-thumb:hover {
        background: #94a3b8 !important;
    }
    
    /* Animação de entrada para as sugestões */
    #address_suggestions {
        animation: slideDown 0.2s ease-out !important;
        transform-origin: top !important;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0 !important;
            transform: translateY(-10px) scale(0.95) !important;
        }
        to {
            opacity: 1 !important;
            transform: translateY(0) scale(1) !important;
        }
    }
    
    /* Melhorar o espaçamento do container de endereço */
    .address-input-container {
        margin-bottom: 1rem !important;
    }
    
    /* Garantir que as sugestões não sobreponham outros elementos */
    .form-group:has(.address-input-container) {
        margin-bottom: 1.5rem !important;
    }
    
    /* Adicionar espaço extra quando as sugestões estão visíveis */
    .address-input-container:has(#address_suggestions:not(.hidden)) {
        margin-bottom: 220px !important; /* Altura das sugestões + margem */
    }
    
    /* Responsividade para dispositivos móveis */
    @media (max-width: 768px) {
        .address-input-container:has(#address_suggestions:not(.hidden)) {
            margin-bottom: 180px !important;
        }
        
        #address_suggestions {
            max-height: 150px !important;
        }
        
        .address-suggestion {
            padding: 10px 14px !important;
            font-size: 0.8rem !important;
        }
    }
    
    /* Estilização especial para o campo de endereço */
    .address-input-container input[name="property_address"] {
        border-bottom-right-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-bottom: 1px solid #d1d5db !important;
    }
    
    /* Quando as sugestões estão visíveis, ajustar o border-radius */
    .address-input-container:has(#address_suggestions:not(.hidden)) input[name="property_address"] {
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom: 1px solid #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
    }

    /* Ajustar z-index dos controles do Leaflet */
    .leaflet-control-zoom {
        z-index: 100 !important;
    }
    
    .leaflet-control-container {
        z-index: 100 !important;
    }

    #map {
        height: 300px;
        width: 100% !important;
        z-index: 10 !important;
        position: relative;
        border-radius: 6px !important;
        margin-top: 0.5rem !important;
        margin-left: 0 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e5e7eb !important;
    }
    
    #map_placeholder {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        min-height: 300px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
    }
    
    .leaflet-container {
        z-index: 10 !important;
        border-radius: 8px !important;
    }

    /* Enhanced error styling */
    .error-container {
        background: #fef2f2 !important;
        border: 1px solid #fecaca !important;
        color: #dc2626 !important;
        padding: 0.75rem 1rem !important;
        border-radius: 8px !important;
        margin-top: 1rem !important;
    }
</style>

<!-- Adicionar CDNs do Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="form-container flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="max-w-4xl w-full px-4 py-8">
        <div class="form-wrapper">
            <h1 class="form-title text-center">Create Network Provisioning</h1>

            <form method="POST" action="{{ route('network-provisioning.store') }}" class="space-y-6">
                @csrf

                <!-- Linha 1: Property Name / Property Type -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">Property Name</label>
                        <input type="text" name="property_name" value="{{ old('property_name') }}" required
                               class="form-input" placeholder="Type the property name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Property Type</label>
                        <select name="property_type" class="form-select">
                            <option value="">Select the property type</option>
                            <option value="Education" {{ old('property_type') == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Healthcare" {{ old('property_type') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Hospitality" {{ old('property_type') == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                            <option value="Industrial" {{ old('property_type') == 'Industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="Mixed-Use" {{ old('property_type') == 'Mixed-Use' ? 'selected' : '' }}>Mixed-Use</option>
                            <option value="Office" {{ old('property_type') == 'Office' ? 'selected' : '' }}>Office</option>
                            <option value="Parking Garage" {{ old('property_type') == 'Parking Garage' ? 'selected' : '' }}>Parking Garage</option>
                            <option value="Residential" {{ old('property_type') == 'Residential' ? 'selected' : '' }}>Residential</option>
                            <option value="Retail" {{ old('property_type') == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Senior Living" {{ old('property_type') == 'Senior Living' ? 'selected' : '' }}>Senior Living</option>
                            <option value="Sports&Events" {{ old('property_type') == 'Sports&Events' ? 'selected' : '' }}>Sports&Events</option>
                            <option value="Warehouse" {{ old('property_type') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                            <option value="Other" {{ old('property_type') == 'Other' ? 'selected' : '' }}>Other</option>  
                        </select>
                    </div>
                </div>

                <!-- Linha 2: Property Address / Remote Unit Quantity -->
                <div class="grid-container">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Property Address</label>
                        <div class="address-input-container">
                            <input type="text" name="property_address" id="property_address" value="{{ old('property_address') }}"
                                   class="form-input" placeholder="Type the property full address" autocomplete="off">
                            <div id="address_suggestions" 
                                class="bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="map-container" id="map_container" style="display: none;">
                    <div id="map"></div>
                </div>
                
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">System Type</label>
                        <select name="system_type" class="form-select">
                            <option value="">Select the system type</option>
                            <option value="DAS" {{ old('system_type') == 'DAS' ? 'selected' : '' }}>DAS</option>
                            <option value="ERRCS" {{ old('system_type') == 'ERRCS' ? 'selected' : '' }}>ERRCS</option>
                            <option value="DAS & ERRCS" {{ old('system_type') == 'DAS & ERRCS' ? 'selected' : '' }}>DAS & ERRCS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">OEM</label>
                        <input type="text" name="oem" value="{{ old('oem') }}"
                               class="form-input" placeholder="Type the OEM">
                    </div>
                </div>
                <!-- Linha 3: Master Unit Quantity / BDA Quantity -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">Master Unit Quantity</label>
                        <input type="number" name="master_unit_quantity" value="{{ old('master_unit_quantity') }}"
                               class="form-input" placeholder="Type the quantity">
                    </div>
                    <div class="form-group">
                        <label class="form-label">BDA Quantity</label>
                        <input type="number" name="bda_quantity" value="{{ old('bda_quantity') }}"
                               class="form-input" placeholder="Type the quantity">
                    </div>
                </div>

                <!-- Linha 4: Latitude / Longitude -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                               class="form-input" placeholder="Type the latitude" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                               class="form-input" placeholder="Type the longitude" readonly>
                    </div>
                </div>

                <!-- Linha 5: Hostname (dyndns) / Create Grafana Credentials -->
<div class="grid-container">
    <!-- Hostname now comes in place of Average Density -->
    <div class="form-group">
        <label class="form-label" for="hostname">Hostname (dyndns)</label>
        <input class="form-input" type="text" id="hostname" name="hostname" value="{{ old('hostname') }}">
    </div>

    <!-- Create Grafana Credentials toggle now comes in place of Remote Unit Quantity -->
    <div class="form-group">
        <label class="form-label">Create Grafana Credentials</label>
        <div class="switch-container">
            <label class="switch">
                <input type="checkbox" id="grafana_toggle" name="grafana_toggle" value="1" {{ old('grafana_toggle') ? 'checked' : '' }} onchange="toggleGrafanaEmail()">
                <span class="slider"></span>
            </label>
        </div>
    </div>
</div>

<!-- Conditional Customer Email field (hidden until toggle is yes) -->
<div id="grafana-email-field" style="display: {{ old('grafana_toggle') ? 'block' : 'none' }};">
    <div class="grid-container">
        <div class="form-group" style="grid-column: 1 / -1;">
            <label class="form-label" for="customer_email">Customer Email ID</label>
            <input class="form-input" type="email" id="customer_email" name="customer_email"
                   value="{{ old('customer_email') }}" placeholder="Enter a valid email address">
        </div>
    </div>
</div>

<!-- Linha 6: Static IP toggle now comes in place of Hostname -->
<div class="grid-container">
    <div class="form-group">
        <label class="form-label" for="static_ip_check">Static IP</label>
        <div class="switch-container">
            <label class="switch">
                <input type="checkbox" id="static_ip_check" name="static_ip_check" value="1"
                       onchange="toggleStaticIpFields()" {{ old('static_ip_check') ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
    </div>
</div>

<div id="static-ip-fields" style="display: {{ old('static_ip_check') ? 'block' : 'none' }};">
    <div class="grid-container">
        <div class="form-group">
            <label class="form-label" for="static_ip">IP Address</label>
            <input class="form-input" type="text" id="static_ip" name="static_ip"
                   value="{{ old('static_ip') }}" placeholder="Type the IP address">
        </div>
        <div class="form-group">
            <label class="form-label" for="static_mask">Subnet Mask</label>
            <input class="form-input" type="text" id="static_mask" name="static_mask"
                   value="{{ old('static_mask') }}" placeholder="Type the subnet mask">
        </div>
    </div>
</div>


                <div id="static-ip-fields" style="display: {{ old('static_ip_check') ? 'block' : 'none' }};">
                    <div class="grid-container">
                        <div class="form-group">
                            <label class="form-label" for="static_ip">IP Address</label>
                            <input class="form-input" type="text" id="static_ip" name="static_ip" value="{{ old('static_ip') }}" placeholder="Type the IP address">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="static_mask">Subnet Mask</label>
                            <input class="form-input" type="text" id="static_mask" name="static_mask" value="{{ old('static_mask') }}" placeholder="Type the subnet mask">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="submit-button">
                        CREATE
                    </button>
                </div>
            </form>
            
            @if($errors->any())
                <div class="error-container">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<footer class="mt-6 border-t border-gray-200 py-4">
	<div class="max-w-4xl mx-auto px-4 text-center text-sm text-gray-600">
		© <script>document.write(new Date().getFullYear())</script> Syndeo Wireless. All rights reserved.
	</div>
</footer>

<script>
class OpenStreetMapHandler {
    constructor(mapElementId = 'map', addressInputId = 'property_address', suggestionsId = 'address_suggestions') {
        this.mapElementId = mapElementId;
        this.addressInputId = addressInputId;
        this.suggestionsId = suggestionsId;
        
        this.map = null;
        this.marker = null;
        this.searchTimeout = null;
        this.selectedCoordinates = null;
        this.selectedAddressText = '';
        
        this.init();
    }
    
    // Inicializar o sistema
    init() {
        // Aguardar o DOM e o Leaflet estarem prontos
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupComponents());
        } else {
            // Se o DOM já está pronto, aguardar um pouco para garantir que o Leaflet foi carregado
            setTimeout(() => this.setupComponents(), 100);
        }
    }
    
    // Configurar todos os componentes
    setupComponents() {
        // Verificar se o Leaflet foi carregado
        if (typeof L === 'undefined') {
            console.error('Leaflet não foi carregado. Tentando novamente...');
            setTimeout(() => this.setupComponents(), 200);
            return;
        }
        
        console.log('Inicializando OpenStreetMap Handler...');
        // Não inicializar o mapa imediatamente - será feito quando um endereço for selecionado
        this.setupEventListeners();
    }
    
    // Inicializar o mapa
    initMap(lat = 40.7589, lng = -73.9851, zoom = 10) {
        try {
            const mapElement = document.getElementById(this.mapElementId);
            if (!mapElement) {
                console.error('Elemento do mapa não encontrado:', this.mapElementId);
                return;
            }
            
            console.log('Inicializando mapa...');
            this.map = L.map(this.mapElementId).setView([lat, lng], zoom);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(this.map);
            
            console.log('Mapa inicializado com sucesso!');
            
            // Adicionar evento de clique no mapa
            this.map.on('click', (e) => {
                this.addMarker(e.latlng.lat, e.latlng.lng);
                this.updateCoordinateFields(e.latlng.lat, e.latlng.lng);
            });
            
            // Invalidar o tamanho do mapa após um pequeno delay para garantir renderização correta
            setTimeout(() => {
                this.map.invalidateSize();
            }, 250);
            
        } catch (error) {
            console.error('Erro ao inicializar mapa:', error);
        }
    }
    
    // Configurar event listeners
    setupEventListeners() {
        const addressInput = document.getElementById(this.addressInputId);
        const suggestionsDiv = document.getElementById(this.suggestionsId);
        
        if (!addressInput || !suggestionsDiv) {
            console.error('Elementos não encontrados. Verifique os IDs dos elementos HTML.');
            return;
        }
        
        console.log('Configurando event listeners...');
        
        // Autocompletar endereço
        addressInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(async () => {
                if (query.length >= 3) {
                    console.log('Buscando endereços para:', query);
                    
                    // Mostrar estado de carregamento
                    suggestionsDiv.innerHTML = '';
                    suggestionsDiv.classList.remove('hidden');
                    suggestionsDiv.classList.add('loading');
                    
                    const suggestions = await this.searchAddresses(query);
                    this.showAddressSuggestions(suggestions);
                } else {
                    suggestionsDiv.classList.add('hidden');
                    suggestionsDiv.classList.remove('loading');
                }
            }, 500); // Aumentei o delay para 500ms para evitar muitas requisições
        });
        
        // Ocultar mapa quando o campo de endereço for limpo
        addressInput.addEventListener('change', (e) => {
            const query = e.target.value.trim();
            if (query === '' || query !== this.selectedAddressText) {
                this.hideMap();
            }
        });
        
        // Mostrar mapa quando coordenadas forem inseridas manualmente
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (latInput && lngInput) {
            latInput.addEventListener('input', () => this.checkManualCoordinates());
            lngInput.addEventListener('input', () => this.checkManualCoordinates());
        }
        
        // Ocultar sugestões quando clicar fora
        document.addEventListener('click', (e) => {
            if (!addressInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
        
        // Ajustar posição das sugestões quando a janela for redimensionada
        window.addEventListener('resize', () => {
            if (!suggestionsDiv.classList.contains('hidden')) {
                this.adjustSuggestionsPosition();
            }
        });
        
        console.log('Event listeners configurados!');
    }
    
    // Buscar endereços usando Nominatim
    async searchAddresses(query) {
        if (query.length < 3) return [];
        
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`;
            console.log('URL da busca:', url);
            
            const response = await fetch(url, {
                headers: {
                    'User-Agent': 'NetworkProvisioningApp/1.0 (Laravel Application)'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Resultados da busca:', data.length, 'endereços encontrados');
            return data;
            
        } catch (error) {
            console.error('Erro ao buscar endereços:', error);
            return [];
        }
    }
    
    // Exibir sugestões de endereço
    showAddressSuggestions(suggestions) {
        const suggestionsDiv = document.getElementById(this.suggestionsId);
        
        if (suggestions.length === 0) {
            suggestionsDiv.classList.add('hidden');
            suggestionsDiv.classList.remove('loading');
            console.log('Nenhuma sugestão encontrada');
            return;
        }
        
        console.log('Mostrando', suggestions.length, 'sugestões');
        suggestionsDiv.classList.remove('loading');
        suggestionsDiv.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.className = 'address-suggestion';
            div.textContent = suggestion.display_name;
            div.onclick = () => this.selectAddress(suggestion);
            suggestionsDiv.appendChild(div);
        });
        
        suggestionsDiv.classList.remove('hidden');
        
        // Ajustar posição se necessário para evitar sobreposição
        this.adjustSuggestionsPosition();
    }
    
    // Ajustar posição das sugestões para evitar sobreposição
    adjustSuggestionsPosition() {
        const suggestionsDiv = document.getElementById(this.suggestionsId);
        const addressInput = document.getElementById(this.addressInputId);
        
        if (!suggestionsDiv || !addressInput) return;
        
        const inputRect = addressInput.getBoundingClientRect();
        const suggestionsRect = suggestionsDiv.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        
        // Verificar se há espaço suficiente abaixo do input
        const spaceBelow = viewportHeight - inputRect.bottom;
        const suggestionsHeight = Math.min(suggestionsRect.height, 200); // max-height
        
        if (spaceBelow < suggestionsHeight + 20) {
            // Se não há espaço suficiente abaixo, mostrar acima do input
            suggestionsDiv.style.top = 'auto';
            suggestionsDiv.style.bottom = '100%';
            suggestionsDiv.style.marginTop = '0';
            suggestionsDiv.style.marginBottom = '4px';
        } else {
            // Posição padrão abaixo do input
            suggestionsDiv.style.top = '100%';
            suggestionsDiv.style.bottom = 'auto';
            suggestionsDiv.style.marginTop = '4px';
            suggestionsDiv.style.marginBottom = '0';
        }
    }
    
    // Selecionar um endereço
    selectAddress(address) {
        console.log('Endereço selecionado:', address.display_name);
        
        const input = document.getElementById(this.addressInputId);
        input.value = address.display_name;
        this.selectedAddressText = address.display_name;
        
        // Ocultar sugestões
        document.getElementById(this.suggestionsId).classList.add('hidden');
        
        // Atualizar mapa
        const lat = parseFloat(address.lat);
        const lng = parseFloat(address.lon);
        
        this.selectedCoordinates = { lat, lng };
        
        // Atualizar campos de coordenadas
        this.updateCoordinateFields(lat, lng);
        
        // Mostrar o container do mapa e ocultar o placeholder
        const mapContainer = document.getElementById('map_container');
        const mapPlaceholder = document.getElementById('map_placeholder');
        
        if (mapContainer) {
            mapContainer.style.display = 'block';
        }
        
        if (mapPlaceholder) {
            mapPlaceholder.style.display = 'none';
        }
        
        // Inicializar o mapa se ainda não foi inicializado
        if (!this.map) {
            this.initMap(lat, lng, 15);
        } else {
            // Adicionar marcador e centralizar mapa
            this.addMarker(lat, lng);
            this.map.setView([lat, lng], 15);
        }
        
        // Invalidar o tamanho do mapa para garantir renderização correta
        setTimeout(() => {
            if (this.map) {
                this.map.invalidateSize();
            }
        }, 100);
    }
    
    // Adicionar marcador no mapa
    addMarker(lat, lng) {
        // Remover marcador anterior se existir
        if (this.marker) {
            this.map.removeLayer(this.marker);
        }
        
        // Adicionar novo marcador
        this.marker = L.marker([lat, lng]).addTo(this.map);
        console.log('Marcador adicionado em:', lat, lng);
    }
    
    // Atualizar campos de coordenadas
    updateCoordinateFields(lat, lng) {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (latInput && lngInput) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            console.log('Coordenadas atualizadas:', lat.toFixed(6), lng.toFixed(6));
        }
    }
    
    // Métodos públicos para acessar dados
    getSelectedCoordinates() {
        return this.selectedCoordinates;
    }
    
    getSelectedAddress() {
        return this.selectedAddressText;
    }
    
    // Método para definir callback personalizado
    setAddressSelectedCallback(callback) {
        this.onAddressSelected = callback;
    }
    
    // Método para definir posição inicial do mapa
    setInitialPosition(lat, lng, zoom = 10) {
        if (this.map) {
            this.map.setView([lat, lng], zoom);
        }
    }
    
    // Ocultar o mapa
    hideMap() {
        const mapContainer = document.getElementById('map_container');
        const mapPlaceholder = document.getElementById('map_placeholder');
        
        if (mapContainer) {
            mapContainer.style.display = 'none';
        }
        
        if (mapPlaceholder) {
            mapPlaceholder.style.display = 'block';
        }
        
        // Limpar coordenadas selecionadas
        this.selectedCoordinates = null;
        this.selectedAddressText = '';
        
        // Limpar campos de coordenadas
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (latInput && lngInput) {
            latInput.value = '';
        }
        if (lngInput) {
            lngInput.value = '';
        }
        
        // Remover marcador se existir
        if (this.marker && this.map) {
            this.map.removeLayer(this.marker);
            this.marker = null;
        }
    }
    
    // Verificar se coordenadas foram inseridas manualmente
    checkManualCoordinates() {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (latInput && lngInput) {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            
            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                // Mostrar mapa e marcar localização
                const mapContainer = document.getElementById('map_container');
                const mapPlaceholder = document.getElementById('map_placeholder');
                
                if (mapContainer) {
                    mapContainer.style.display = 'block';
                }
                
                if (mapPlaceholder) {
                    mapPlaceholder.style.display = 'none';
                }
                
                // Inicializar mapa se necessário
                if (!this.map) {
                    this.initMap(lat, lng, 15);
                } else {
                    this.map.setView([lat, lng], 15);
                }
                
                // Adicionar marcador
                this.addMarker(lat, lng);
                
                // Atualizar coordenadas selecionadas
                this.selectedCoordinates = { lat, lng };
                
                // Invalidar tamanho do mapa
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }, 100);
            }
        }
    }
}

// Função para toggle dos campos de IP estático
function toggleStaticIpFields() {
    var checkbox = document.getElementById('static_ip_check');
    var fields = document.getElementById('static-ip-fields');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}

// Inicializar o mapa quando a página estiver pronta
let mapHandler;

// Aguardar tanto o DOM quanto o Leaflet
document.addEventListener('DOMContentLoaded', function() {
    // Pequeno delay para garantir que todos os recursos foram carregados
    setTimeout(() => {
        mapHandler = new OpenStreetMapHandler();
        
        // Verificar se há coordenadas existentes (por exemplo, de validação de formulário)
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (latInput && lngInput && latInput.value && lngInput.value) {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                // Se há coordenadas válidas, mostrar o mapa
                setTimeout(() => {
                    mapHandler.checkManualCoordinates();
                }, 200);
            }
        }
    }, 100);
});

function toggleGrafanaEmail() {
    const toggle = document.getElementById('grafana_toggle');
    const emailField = document.getElementById('grafana-email-field');
    emailField.style.display = toggle.checked ? 'block' : 'none';
}

</script>

@endsection