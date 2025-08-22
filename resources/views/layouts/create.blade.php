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
        <div class="main-form-container">
            <h1 class="form-title">Create Network Provisioning</h1>

            <form method="POST" action="" class="space-y-8">
                @csrf

                <!-- Linha 1: Property Name / OEM -->
                <div class="grid grid-cols-1 md:grid-cols-2 form-grid-gap form-section-spacing">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Property Name</label>
                        <input type="text" name="property_name" required
                               class="w-full px-4 py-3 border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               style="border-radius: 12px !important; border-width: 2px !important; padding: 12px 16px !important;"
                               placeholder="Type the property name">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">OEM</label>
                        <input type="text" name="oem" required
                               class="w-full px-4 py-3 border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               style="border-radius: 12px !important; border-width: 2px !important; padding: 12px 16px !important;"
                               placeholder="Type the OEM">
                    </div>
                </div>

                <!-- Linha 2: Property Address / Remote Unit Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-2 form-grid-gap form-section-spacing">
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Property Address</label>
                        <div class="relative">
                            <input type="text" 
                                   id="property_address" 
                                   placeholder="Digite o endereço completo"
                                   autocomplete="off"
                                   class="w-full px-4 py-3 border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400">
                            <div id="address_suggestions" 
                                 class="absolute z-50 w-full bg-white border-2 border-gray-200 shadow-xl hidden max-h-48 overflow-y-auto mt-1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">Remote Unit Quantity</label>
                        <input type="number" name="remote_unit_quantity" required
                               class="w-full px-4 py-3 border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
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
                        <input type="number" name="master_unit_quantity" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                               placeholder="Type the quantity">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-semibold mb-3 text-sm uppercase tracking-wide">BDA Quantity</label>
                        <input type="number" name="bda_quantity" 
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
                <div class="button-container">
                    <button type="submit" class="form-submit-button">
                        Create Provisioning
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Force all form styling with !important to override existing CSS */
    .form-group input {
        border-radius: 12px !important;
        border-width: 2px !important;
        border-color: #e5e7eb !important;
        padding: 12px 16px !important;
        transition: all 0.2s ease !important;
    }

    .form-group select {
        border-radius: 12px !important;
        border-width: 2px !important;
        border-color: #e5e7eb !important;
        padding: 12px 16px !important;
        background-color: white !important;
        transition: all 0.2s ease !important;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
        border-color: transparent !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }

    .form-group label {
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        font-size: 0.875rem !important;
        color: #374151 !important;
        margin-bottom: 12px !important;
        display: block !important;
    }

    /* Enhanced styles for address suggestions */
    #address_suggestions {
        top: 100% !important;
        left: 0 !important;
        z-index: 1000 !important;
        backdrop-filter: blur(8px) !important;
        border-radius: 12px !important;
        border-width: 2px !important;
        border-color: #e5e7eb !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        margin-top: 4px !important;
    }
    
    .address-suggestion {
        padding: 12px 16px !important;
        cursor: pointer !important;
        border-bottom: 1px solid #f3f4f6 !important;
        background-color: white !important;
        transition: all 0.15s ease !important;
        font-size: 14px !important;
        color: #4b5563 !important;
    }
    
    .address-suggestion:hover {
        background-color: #f8fafc !important;
        transform: translateX(4px) !important;
        border-left: 3px solid #3b82f6 !important;
    }
    
    .address-suggestion:last-child {
        border-bottom: none !important;
        border-radius: 0 0 12px 12px !important;
    }

    .address-suggestion:first-child {
        border-radius: 12px 12px 0 0 !important;
    }

    /* Enhanced Leaflet map controls */
    .leaflet-control-zoom {
        z-index: 100 !important;
        border-radius: 8px !important;
        overflow: hidden !important;
    }
    
    .leaflet-control-container {
        z-index: 100 !important;
    }

    #map {
        z-index: 10 !important;
        position: relative !important;
        border-radius: 12px !important;
        border-width: 2px !important;
        border-color: #e5e7eb !important;
        height: 288px !important;
    }
    
    .leaflet-container {
        z-index: 10 !important;
        border-radius: 12px !important;
    }

    /* Main form container styling */
    .main-form-container {
        background-color: white !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        border: 1px solid #f3f4f6 !important;
        padding: 40px !important;
    }

    /* Form spacing */
    .form-section-spacing {
        margin-bottom: 32px !important;
    }

    .form-grid-gap {
        gap: 32px !important;
    }

    /* Button styling */
    .form-submit-button {
        padding: 16px 48px !important;
        font-size: 1.125rem !important;
        font-weight: 600 !important;
        border-radius: 12px !important;
        background-color: #13395d !important;
        color: white !important;
        border: 3px solid #fbbf0f !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .form-submit-button:hover {
        opacity: 0.9 !important;
        transform: scale(1.05) !important;
    }

    .form-submit-button:focus {
        outline: none !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3), 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    /* Button container */
    .button-container {
        padding-top: 32px !important;
        padding-bottom: 24px !important;
        text-align: center !important;
    }

    /* Title styling */
    .form-title {
        font-size: 1.875rem !important;
        font-weight: 700 !important;
        margin-bottom: 40px !important;
        padding-top: 24px !important;
        text-align: center !important;
        color: #1f2937 !important;
        letter-spacing: -0.025em !important;
    }

    /* Custom scrollbar for address suggestions */
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