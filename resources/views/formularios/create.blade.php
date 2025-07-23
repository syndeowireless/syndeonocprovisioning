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

@section("content")
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

<div id="layout-wrapper" class="flex justify-center w-full min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="w-full max-w-4xl px-4 mx-auto py-8">
        <div class="bg-white rounded-xl shadow-lg p-8" style="margin-top: 10%">
            <h1 class="text-2xl font-bold mb-8 text-center text-black">Create New ROM</h1>

            <form method="POST" action="{{ route("sales.rom-generator.store") }}" class="space-y-6">
                @csrf

                <!-- Linha 1: Property Name / Parking Area -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Property Name</label>
                        <input type="text" name="property_name" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the property name">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Parking Area</label>
                        <input type="text" name="parking_area" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the parking area">
                    </div>
                </div>

                <!-- Linha 2: Property Address / Connection Between Buildings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Property Address</label>
                        <div class="relative">
                            <input type="text" name="property_address" id="property_address" required
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Type the property full address"
                                   autocomplete="off">
                            <!-- Lista de sugestões de endereços -->
                            <div id="address_suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Connection Between Buildings?</label>
                        <div class="flex items-center">
                            <div class="relative inline-block w-20 mr-3 align-middle select-none">
                                <input 
                                    type="checkbox" 
                                    name="connection_between_buildings" 
                                    id="connection_toggle"
                                    class="toggle-checkbox absolute block w-8 h-8 rounded-full bg-white border-4 appearance-none cursor-pointer transition-transform duration-200 ease-in-out"
                                    checked
                                >
                                <label 
                                    for="connection_toggle" 
                                    class="toggle-label block overflow-hidden h-8 rounded-full bg-gray-300 cursor-pointer"
                                ></label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa (ocupando toda a largura) -->
                <div class="form-group">
                    <label class="block text-gray-700 font-medium mb-2 text-black">Property Location</label>
                    <div id="map" class="w-full h-64 border rounded-lg"></div>
                </div>

                <!-- Linha 3: Property Type / Average Density -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Property Type</label>
                        <select name="property_type" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select the property type</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Factory">Factory</option>
                            <option value="Office">Office</option>
                            <option value="Residencial">Residencial</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Average Density</label>
                        <select name="average_density" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select the density</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 4: Number of Floors / Construction Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Number of Floors</label>
                        <input type="number" name="floors" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the total number of floors">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Construction Status</label>
                        <select name="construction_status" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select the construction status</option>
                            <option value="Project">Project</option>
                            <option value="Constructed">Constructed</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 5: Number of Buildings / Type of System -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Number of Buildings</label>
                        <input type="number" name="buildings" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the total number of buildings">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Type of System</label>
                        <select name="system_type" required
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select the system type</option>
                            <option value="DAS">DAS</option>
                            <option value="ERRCS">ERRCS</option>
                            <option value="DAS & ERRCS">DAS & ERRCS</option>
                        </select>
                    </div>
                </div>

                <!-- Linha 6: Coverage Area (full width) -->
                <div class="form-group">
                    <label class="block text-gray-700 font-medium mb-2 text-black">Coverage Area</label>
                    <input type="text" name="coverage_area" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Type the coverage area without parking">
                </div>

                <!-- Submit Button -->
                <div class="form-group pt-6 text-center">
                    <button type="submit" 
                            class="px-8 py-3 font-medium rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all"
                            style="background-color: #d9ff35; color: black;">
                        Create ROM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="rounded-lg p-6 w-full max-w-md relative bg-gray-800">
        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="text-center mb-4">
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <h3 class="text-lg font-medium text-white">Your <span id="romCodeDisplay"></span> is ready</h3>
            <p class="text-sm text-gray-300">and the pdf was sent to your e-mail</p>
        </div>
        
        <div class="flex justify-between space-x-4">
            <a id="downloadButton" href="#" style="background-color: #d9ff35;color: black" class="flex-1 hover:bg-blue-600 text-white py-2 px-4 rounded flex items-center justify-center">
                <svg width="23" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path 
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" 
                    stroke="#000000"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
                <div style="color: Black;">Download</div>
            </a>
            <a id="updateButton" href="#" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Update
            </a>
        </div>
    </div>
</div>

<style>
    /* Estilos globais para garantir a centralização */
    #layout-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    input, select {
        transition: all 0.3s ease;
    }
    
    input:focus, select:focus {
        border-color: rgba(0, 0, 0, 0);
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);
    }
    
    .toggle-checkbox:checked {
        transform: translateX(2.5rem);
        border-color: #d9ff35;
        background-color: black;
    }
    
    .toggle-checkbox:checked + .toggle-label {
        background-color: #d9ff35;
    }
    
    /* Corrige possíveis problemas de layout */
    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
    }
    
    /* Garante que o conteúdo principal ocupe o espaço disponível */
    .main-content {
        flex: 1;
    }

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

    /* Ajustar z-index dos controles de zoom do Leaflet */
    .leaflet-control-zoom {
        z-index: 100 !important;
    }
    
    .leaflet-control-container {
        z-index: 100 !important;
    }

    /* Garantir que o mapa fique abaixo da modal de sucesso */
    #map {
        z-index: 10 !important;
        position: relative;
    }
    
    .leaflet-container {
        z-index: 10 !important;
    }
</style>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script>
    // Variáveis globais para o mapa
    let map;
    let marker;
    let searchTimeout;

    // Inicializar o mapa
    function initMap() {
        // Criar o mapa centrado no Brasil (São Paulo)
        map = L.map('map').setView([40.7081, -74.0061], 10);
        
        // Adicionar camada do OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    }

    // Função para buscar endereços usando Nominatim
    async function searchAddresses(query) {
        if (query.length < 3) return [];
        
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&accept-language=en`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Erro ao buscar endereços:', error);
            return [];
        }
    }

    // Função para exibir sugestões de endereço
    function showAddressSuggestions(suggestions) {
        const suggestionsDiv = document.getElementById('address_suggestions');
        
        if (suggestions.length === 0) {
            suggestionsDiv.classList.add('hidden');
            return;
        }
        
        suggestionsDiv.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const div = document.createElement('div');
            div.className = 'address-suggestion';
            div.textContent = suggestion.display_name;
            div.onclick = () => selectAddress(suggestion);
            suggestionsDiv.appendChild(div);
        });
        
        suggestionsDiv.classList.remove('hidden');
    }

    // Função para selecionar um endereço
    function selectAddress(address) {
        const input = document.getElementById('property_address');
        input.value = address.display_name;
        
        // Ocultar sugestões
        document.getElementById('address_suggestions').classList.add('hidden');
        
        // Atualizar mapa
        const lat = parseFloat(address.lat);
        const lon = parseFloat(address.lon);
        
        // Remover marcador anterior se existir
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Adicionar novo marcador
        marker = L.marker([lat, lon]).addTo(map);
        
        // Centralizar mapa no endereço
        map.setView([lat, lon], 15);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar mapa
        initMap();
        
        const addressInput = document.getElementById('property_address');
        const suggestionsDiv = document.getElementById('address_suggestions');
        
        // Autocompletar endereço
        addressInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Limpar timeout anterior
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Definir novo timeout para evitar muitas requisições
            searchTimeout = setTimeout(async () => {
                if (query.length >= 3) {
                    const suggestions = await searchAddresses(query);
                    showAddressSuggestions(suggestions);
                } else {
                    suggestionsDiv.classList.add('hidden');
                }
            }, 300);
        });
        
        // Ocultar sugestões quando clicar fora
        document.addEventListener('click', function(e) {
            if (!addressInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    });

    // Funções existentes da modal
    function showModal(romCode, romId) {
        document.getElementById("romCodeDisplay").textContent = romCode;
        
        // Atualiza os links dos botões
        document.getElementById("downloadButton").href = "{{ route("sales.rom-generator.download", "__ROM_ID__") }}".replace("__ROM_ID__", romId);
        document.getElementById("updateButton").href = "{{ route("sales.rom-generator.edit", "__ROM_ID__") }}".replace("__ROM_ID__", romId);

        document.getElementById("successModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("successModal").classList.add("hidden");
    }

    @if(session("show_modal") && session("rom_code") && session("rom_id"))
        showModal("{{ session("rom_code") }}", "{{ session("rom_id") }}"); 
    @endif

    document.getElementById("connection_toggle").addEventListener("change", function() {
        this.value = this.checked ? "1" : "0";
    });
</script>
@endsection

