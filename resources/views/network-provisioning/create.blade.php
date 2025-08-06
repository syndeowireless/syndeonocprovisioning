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
</style>

<style>
    /* Estilos para as sugestões de endereço */
    #address_suggestions {
        top: 100%;
        left: 0;
        z-index: 1000;
    }
    
    .address-suggestion {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        background-color: white;
    }
    
    .address-suggestion:hover {
        background-color: #f5f5f5;
    }
    
    .address-suggestion:last-child {
        border-bottom: none;
    }

    /* Ajustar z-index dos controles do Leaflet */
    .leaflet-control-zoom {
        z-index: 100 !important;
    }
    
    .leaflet-control-container {
        z-index: 100 !important;
    }

    #map {
        z-index: 10 !important;
        position: relative;
    }
    
    .leaflet-container {
        z-index: 10 !important;
    }
</style>
<div class="flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="max-w-4xl px-4 py-8" style="width: 80%;padding-top: 5%;">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-2xl font-bold mb-8 text-center text-black">Create Network Provisioning</h1>

            <form method="POST" action="{{ route('network-provisioning.store') }}" class="space-y-6">
                @csrf

                <!-- Linha 1: Property Name / OEM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" style="margin-left: 5%">Property Name</label>
                        <input type="text" name="property_name" value="{{ old('property_name') }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the property name" style="width: 90%;margin-left: 5%;">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">OEM</label>
                        <input type="text" name="oem" value="{{ old('oem') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the OEM" style="width: 95%;">
                    </div>
                </div>

                <!-- Linha 2: Property Address / Remote Unit Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" style="margin-left: 5%">Property Address</label>
                        <input type="text" name="property_address" value="{{ old('property_address') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the property full address" style="width: 90%;margin-left: 5%;">
                            <div id="address_suggestions" 
                                class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
                            </div>
                            <div id="map" class="w-full h-64 border rounded-lg"></div>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Remote Unit Quantity</label>
                        <input type="number" name="remote_unit_quantity" value="{{ old('remote_unit_quantity') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the quantity" style="width: 95%;">
                    </div>
                </div>

                <!-- Linha 3: Master Unit Quantity / BDA Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" style="margin-left: 5%">Master Unit Quantity</label>
                        <input type="number" name="master_unit_quantity" value="{{ old('master_unit_quantity') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the quantity" style="width: 90%;margin-left: 5%;">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">BDA Quantity</label>
                        <input type="number" name="bda_quantity" value="{{ old('bda_quantity') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the quantity" style="width: 95%;">
                    </div>
                </div>

                <!-- Linha 4: Latitude / Longitude -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" style="margin-left: 5%">Latitude</label>
                        <input type="text" name="latitude" value="{{ old('latitude') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the latitude" style="width: 90%;margin-left: 5%;">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Longitude</label>
                        <input type="text" name="longitude" value="{{ old('longitude') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the longitude">
                    </div>
                </div>

                <!-- Linha 5: Property Type / Average Density -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" style="margin-left: 5%">Property Type</label>
                        <select name="property_type"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" style="width: 90%;margin-left: 5%;" >
                            <option value="">Select the property type</option>
                            <option value="Hotel" {{ old('property_type') == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Hotel" {{ old('property_type') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Hotel" {{ old('property_type') == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                            <option value="Hotel" {{ old('property_type') == 'Industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="Hotel" {{ old('property_type') == 'Mixed-Use' ? 'selected' : '' }}>Mixed-Use</option>
                            <option value="Hotel" {{ old('property_type') == 'Office' ? 'selected' : '' }}>Office</option>
                            <option value="Hotel" {{ old('property_type') == 'Parking Garage' ? 'selected' : '' }}>Parking Garage</option>
                            <option value="Hotel" {{ old('property_type') == 'Residential' ? 'selected' : '' }}>Residential</option>
                            <option value="Hotel" {{ old('property_type') == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Hotel" {{ old('property_type') == 'Senior Living' ? 'selected' : '' }}>Senior Living</option>
                            <option value="Hotel" {{ old('property_type') == 'Sports&Events' ? 'selected' : '' }}>Sports&Events</option>
                            <option value="Hotel" {{ old('property_type') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                            <option value="Hotel" {{ old('property_type') == 'Other' ? 'selected' : '' }}>Other</option>  
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Average Density</label>
                        <select name="average_density"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" style="width: 95%;">
                            <option value="">Select the density</option>
                            <option value="Low" {{ old('average_density') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('average_density') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('average_density') == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 6: System Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" style="margin-left: 5%" >System Type</label>
                        <select name="system_type"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" style="width: 90%;margin-left: 5%;">
                            <option value="">Select the system type</option>
                            <option value="DAS" {{ old('system_type') == 'DAS' ? 'selected' : '' }}>DAS</option>
                            <option value="ERRCS" {{ old('system_type') == 'ERRCS' ? 'selected' : '' }}>ERRCS</option>
                            <option value="DAS & ERRCS" {{ old('system_type') == 'DAS & ERRCS' ? 'selected' : '' }}>DAS & ERRCS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black" for="hostname">Hostname (dyndns)</label>
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 form-control" type="text" id="hostname" name="hostname" style="width: 95%;">
                    </div>
                </div>



                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black form-check-label switch" for="static_ip_check" style="margin-left: 5%">Static IP</label>
                        
                        <label class="switch">
                          <input type="checkbox" id="static_ip_check" name="static_ip_check" value="1" onchange="toggleStaticIpFields()">
                          <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <div id="static-ip-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label class="block text-gray-700 font-medium mb-2 text-black" for="static_ip" style="margin-left: 5%">IP</label>
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 form-control" type="text" id="static_ip" name="static_ip" placeholder="Type the IP" style="width: 50%;margin-left: 3%;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="block text-gray-700 font-medium mb-2 text-black" for="static_mask" style="margin-left: 5%">Mask</label>
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 form-control" type="text" id="static_mask" name="static_mask" placeholder="Type the Mask" style="width: 50%;margin-left: 3%;">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group pt-6 text-center">
                    <button type="submit" 
                            class="px-8 py-3 font-medium rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all"
                           style="background-color: #13395d;color: white;border: 2px solid #fbbf0f;padding-right: 10px;padding-left: 10px; width: 15%">
                        Create
                    </button>
                </div>
            </form>
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
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
        // Aguardar o DOM estar pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupComponents());
        } else {
            this.setupComponents();
        }
    }
    
    // Configurar todos os componentes
    setupComponents() {
        this.initMap();
        this.setupEventListeners();
    }
    
    // Inicializar o mapa
    initMap(lat = 40.7081, lng = -74.0061, zoom = 10) {
        this.map = L.map(this.mapElementId).setView([lat, lng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);
    }
    
    // Configurar event listeners
    setupEventListeners() {
        const addressInput = document.getElementById(this.addressInputId);
        const suggestionsDiv = document.getElementById(this.suggestionsId);
        
        if (!addressInput || !suggestionsDiv) {
            console.error('Elementos não encontrados. Verifique os IDs dos elementos HTML.');
            return;
        }
        
        // Autocompletar endereço
        addressInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(async () => {
                if (query.length >= 3) {
                    const suggestions = await this.searchAddresses(query);
                    this.showAddressSuggestions(suggestions);
                } else {
                    suggestionsDiv.classList.add('hidden');
                }
            }, 300);
        });
        
        // Ocultar sugestões quando clicar fora
        document.addEventListener('click', (e) => {
            if (!addressInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    }
    
    // Buscar endereços usando Nominatim
    async searchAddresses(query) {
        if (query.length < 3) return [];
        
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&accept-language=en`
            );
            const data = await response.json();
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
            return;
        }
        
        suggestionsDiv.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.className = 'address-suggestion';
            div.textContent = suggestion.display_name;
            div.onclick = () => this.selectAddress(suggestion);
            suggestionsDiv.appendChild(div);
        });
        
        suggestionsDiv.classList.remove('hidden');
    }
    
    // Selecionar um endereço
    selectAddress(address) {
        const input = document.getElementById(this.addressInputId);
        input.value = address.display_name;
        this.selectedAddressText = address.display_name;
        
        // Ocultar sugestões
        document.getElementById(this.suggestionsId).classList.add('hidden');
        
        // Atualizar mapa
        const lat = parseFloat(address.lat);
        const lng = parseFloat(address.lon);
        
        this.selectedCoordinates = { lat, lng };
        
        // Remover marcador anterior se existir
        if (this.marker) {
            this.map.removeLayer(this.marker);
        }
        
        // Adicionar novo marcador
        this.marker = L.marker([lat, lng]).addTo(this.map);
        
        // Centralizar mapa no endereço
        this.map.setView([lat, lng], 15);
        
        // Callback personalizado (se definido)
        if (this.onAddressSelected) {
            this.onAddressSelected({
                address: address.display_name,
                coordinates: { lat, lng }
            });
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
}    
</script>
<script>
function toggleStaticIpFields() {
    var checkbox = document.getElementById('static_ip_check');
    var fields = document.getElementById('static-ip-fields');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}
</script>


@endsection

