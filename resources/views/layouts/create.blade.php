@if(session("success"))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session("success") }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@extends("layouts.app")

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section("content")
<div class="flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="max-w-4xl px-6 py-10" style="width: 85%;padding-top: 3%;">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-10">
            <h1 class="text-3xl font-bold mb-10 pt-6 text-center text-gray-800 tracking-tight">Create Network Provisioning</h1>

            <form method="POST" action="" class="space-y-8">
                @csrf

                <!-- Linha 1: Property Name / OEM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Property Name</label>
                        <input type="text" name="property_name" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the property name">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">OEM</label>
                        <input type="text" name="oem" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the OEM">
                    </div>
                </div>

                <!-- Linha 2: Property Address / Remote Unit Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Property Address</label>
                        <div class="relative">
                            <input type="text" 
                                   id="property_address" 
                                   placeholder="Digite o endereço completo"
                                   autocomplete="off"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400">
                            <div id="address_suggestions" 
                                 class="absolute z-50 w-full bg-white border-2 border-gray-200 rounded-xl shadow-xl hidden max-h-48 overflow-y-auto mt-1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Remote Unit Quantity</label>
                        <input type="number" name="remote_unit_quantity" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the quantity">
                    </div>
                </div>

                <!-- Map Section -->
                <div class="form-group">
                    <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Location Map</label>
                    <div id="map" class="w-full h-72 border-2 border-gray-200 rounded-xl shadow-inner"></div>
                </div>

                <!-- Linha 3: Master Unit Quantity / BDA Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Master Unit Quantity</label>
                        <input type="number" name="master_unit_quantity" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the quantity">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">BDA Quantity</label>
                        <input type="number" name="bda_quantity" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the quantity">
                    </div>
                </div>

                <!-- Linha 4: Latitude / Longitude -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Latitude</label>
                        <input type="text" name="latitude" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the latitude">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Longitude</label>
                        <input type="text" name="longitude" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the longitude">
                    </div>
                </div>

                <!-- Linha 5: Property Type / Average Density -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Property Type</label>
                        <select name="property_type" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 bg-white">
                            <option value="">Select the property type</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Factory">Factory</option>
                            <option value="Office">Office</option>
                            <option value="Residencial">Residencial</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Average Density</label>
                        <select name="average_density" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 bg-white">
                            <option value="">Select the density</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 6: System Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">System Type</label>
                        <select name="system_type" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 bg-white">
                            <option value="">Select the system type</option>
                            <option value="DAS">DAS</option>
                            <option value="ERRCS">ERRCS</option>
                            <option value="DAS & ERRCS">DAS & ERRCS</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group pt-8 pb-6 text-center">
                    <button type="submit" 
                            class="px-12 py-4 font-semibold text-lg rounded-xl hover:opacity-90 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 shadow-lg"
                           style="background-color: #13395d;color: white;border: 3px solid #fbbf0f;">
                        Create Provisioning
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Enhanced styles for address suggestions */
    #address_suggestions {
        top: 100%;
        left: 0;
        z-index: 1000;
        backdrop-filter: blur(8px);
    }
    
    .address-suggestion {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
        background-color: white;
        transition: all 0.15s ease;
        font-size: 14px;
        color: #4b5563;
    }
    
    .address-suggestion:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
        border-left: 3px solid #3b82f6;
    }
    
    .address-suggestion:last-child {
        border-bottom: none;
        border-radius: 0 0 12px 12px;
    }

    .address-suggestion:first-child {
        border-radius: 12px 12px 0 0;
    }

    /* Enhanced Leaflet map controls */
    .leaflet-control-zoom {
        z-index: 100 !important;
        border-radius: 8px !important;
        overflow: hidden;
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

    /* Enhanced form input focus animations */
    .form-group input:focus,
    .form-group select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Custom scrollbar for address suggestions */
    #address_suggestions::-webkit-scrollbar {
        width: 6px;
    }

    #address_suggestions::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    #address_suggestions::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    #address_suggestions::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

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
@endsection