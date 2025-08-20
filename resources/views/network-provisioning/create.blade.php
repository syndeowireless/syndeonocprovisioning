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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    min-height: 100vh !important;
    padding: 2rem 0 !important;
}

.form-wrapper {
    background: rgba(255, 255, 255, 0.95) !important;
    border-radius: 24px !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.05) !important;
    padding: 3rem !important;
    backdrop-filter: blur(10px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.form-title {
    padding-top: 1.5rem !important;
    padding-bottom: 2rem !important;
    font-size: 2rem !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
    letter-spacing: -0.025em !important;
    margin-bottom: 2.5rem !important;
}

.form-group {
    margin-bottom: 2rem !important;
}

.form-label {
    display: block !important;
    color: #374151 !important;
    font-weight: 600 !important;
    margin-bottom: 0.75rem !important;
    font-size: 0.95rem !important;
    letter-spacing: 0.01em !important;
}

.form-input, .form-select {
    width: 100% !important;
    padding: 0.875rem 1rem !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    font-size: 0.95rem !important;
    transition: all 0.3s ease !important;
    background: rgba(255, 255, 255, 0.9) !important;
    color: #1f2937 !important;
}

.form-input:focus, .form-select:focus {
    outline: none !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    background: rgba(255, 255, 255, 1) !important;
}

.form-input::placeholder {
    color: #9ca3af !important;
    font-weight: 400 !important;
}

.submit-button {
    background: linear-gradient(135deg, #13395d 0%, #1e5a8a 100%) !important;
    color: white !important;
    border: 2px solid #fbbf0f !important;
    padding: 1rem 2.5rem !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    letter-spacing: 0.025em !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(19, 57, 93, 0.3) !important;
    margin-top: 2rem !important;
    margin-bottom: 2rem !important;
    min-width: 150px !important;
}

.submit-button:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(19, 57, 93, 0.4) !important;
    background: linear-gradient(135deg, #1e5a8a 0%, #13395d 100%) !important;
}

.grid-container {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 2rem !important;
    margin-bottom: 1.5rem !important;
}

@media (max-width: 768px) {
    .grid-container {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    
    .form-wrapper {
        padding: 2rem !important;
        border-radius: 20px !important;
    }
    
    .form-title {
        font-size: 1.75rem !important;
    }
}
</style>

<style>
    /* Estilos para as sugestões de endereço */
    .address-input-container {
        position: relative;
    }
    
    #address_suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
    }
    
    .address-suggestion {
        padding: 12px 16px !important;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
        background-color: white;
        transition: all 0.2s ease !important;
        font-size: 0.9rem !important;
    }
    
    .address-suggestion:hover {
        background-color: #f8fafc !important;
        padding-left: 20px !important;
    }
    
    .address-suggestion:last-child {
        border-bottom: none;
        border-radius: 0 0 12px 12px !important;
    }
    
    .address-suggestion:first-child {
        border-radius: 12px 12px 0 0 !important;
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
        border-radius: 12px !important;
        margin-top: 15px !important;
        margin-left: 0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        border: 2px solid #e5e7eb !important;
    }
    
    .leaflet-container {
        z-index: 10 !important;
        border-radius: 12px !important;
    }

    /* Enhanced error styling */
    .error-container {
        background: rgba(239, 68, 68, 0.1) !important;
        border: 2px solid rgba(239, 68, 68, 0.3) !important;
        color: #dc2626 !important;
        padding: 1rem 1.5rem !important;
        border-radius: 12px !important;
        margin-top: 1.5rem !important;
        backdrop-filter: blur(10px) !important;
    }
</style>

<!-- Adicionar CDNs do Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="form-container flex justify-center items-center min-h-screen">
    <div class="max-w-5xl w-full px-6">
        <div class="form-wrapper">
            <h1 class="form-title text-center">Create Network Provisioning</h1>

            <form method="POST" action="{{ route('network-provisioning.store') }}" class="space-y-6">
                @csrf

                <!-- Linha 1: Property Name / OEM -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">Property Name</label>
                        <input type="text" name="property_name" value="{{ old('property_name') }}" required
                               class="form-input" placeholder="Type the property name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">OEM</label>
                        <input type="text" name="oem" value="{{ old('oem') }}"
                               class="form-input" placeholder="Type the OEM">
                    </div>
                </div>

                <!-- Linha 2: Property Address / Remote Unit Quantity -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">Property Address</label>
                        <div class="address-input-container">
                            <input type="text" name="property_address" id="property_address" value="{{ old('property_address') }}"
                                   class="form-input" placeholder="Type the property full address" autocomplete="off">
                            <div id="address_suggestions" 
                                class="bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                            </div>
                        </div>
                        <div id="map"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Remote Unit Quantity</label>
                        <input type="number" name="remote_unit_quantity" value="{{ old('remote_unit_quantity') }}"
                               class="form-input" placeholder="Type the quantity">
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

                <!-- Linha 5: Property Type / Average Density -->
                <div class="grid-container">
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
                    <div class="form-group">
                        <label class="form-label">Average Density</label>
                        <select name="average_density" class="form-select">
                            <option value="">Select the density</option>
                            <option value="Low" {{ old('average_density') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('average_density') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('average_density') == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 6: System Type -->
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
                        <label class="form-label" for="hostname">Hostname (dyndns)</label>
                        <input class="form-input" type="text" id="hostname" name="hostname" value="{{ old('hostname') }}">
                    </div>
                </div>

                <div class="grid-container">
                    <div class="form-group">
                        <div class="flex items-center">
                            <label class="form-label mr-4" for="static_ip_check">
                                Static IP
                            </label>
                            <label class="switch ml-3">
                                <input type="checkbox" id="static_ip_check" name="static_ip_check" value="1" onchange="toggleStaticIpFields()" {{ old('static_ip_check') ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
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
                        Create Network Provisioning
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
        this.initMap();
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
                    const suggestions = await this.searchAddresses(query);
                    this.showAddressSuggestions(suggestions);
                } else {
                    suggestionsDiv.classList.add('hidden');
                }
            }, 500); // Aumentei o delay para 500ms para evitar muitas requisições
        });
        
        // Ocultar sugestões quando clicar fora
        document.addEventListener('click', (e) => {
            if (!addressInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
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
            console.log('Nenhuma sugestão encontrada');
            return;
        }
        
        console.log('Mostrando', suggestions.length, 'sugestões');
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
        
        // Adicionar marcador e centralizar mapa
        this.addMarker(lat, lng);
        this.map.setView([lat, lng], 15);
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
    }, 100);
});
</script>

@endsection