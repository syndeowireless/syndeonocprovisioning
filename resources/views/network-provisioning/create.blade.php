@if(session("success"))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 animate-slideDown">
        {{ session("success") }}
    </div>
@endif

@extends("layouts.app")

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section("content")
<style>
    /* Page Entry Animations */
    .page-container {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .form-wrapper {
        animation: slideInScale 0.9s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        animation-fill-mode: both;
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    
    .form-title {
        animation: slideInDown 0.7s ease-out 0.2s both;
    }
    
    .form-group {
        opacity: 0;
        transform: translateX(-20px);
        animation: slideInLeft 0.6s ease-out both;
    }
    
    /* Stagger animations for form groups */
    .form-group:nth-child(1) { animation-delay: 0.3s; }
    .form-group:nth-child(2) { animation-delay: 0.35s; }
    .form-group:nth-child(3) { animation-delay: 0.4s; }
    .form-group:nth-child(4) { animation-delay: 0.45s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    .form-group:nth-child(6) { animation-delay: 0.55s; }
    .form-group:nth-child(7) { animation-delay: 0.6s; }
    .form-group:nth-child(8) { animation-delay: 0.65s; }
    .form-group:nth-child(9) { animation-delay: 0.7s; }
    .form-group:nth-child(10) { animation-delay: 0.75s; }
    
    .grid-container:nth-child(1) .form-group:nth-child(1) { animation-delay: 0.3s; }
    .grid-container:nth-child(1) .form-group:nth-child(2) { animation-delay: 0.4s; }
    .grid-container:nth-child(2) .form-group { animation-delay: 0.5s; }
    .grid-container:nth-child(3) .form-group:nth-child(1) { animation-delay: 0.6s; }
    .grid-container:nth-child(3) .form-group:nth-child(2) { animation-delay: 0.65s; }
    .grid-container:nth-child(4) .form-group:nth-child(1) { animation-delay: 0.7s; }
    .grid-container:nth-child(4) .form-group:nth-child(2) { animation-delay: 0.75s; }
    .grid-container:nth-child(5) .form-group:nth-child(1) { animation-delay: 0.8s; }
    .grid-container:nth-child(5) .form-group:nth-child(2) { animation-delay: 0.85s; }
    .grid-container:nth-child(6) .form-group:nth-child(1) { animation-delay: 0.9s; }
    .grid-container:nth-child(6) .form-group:nth-child(2) { animation-delay: 0.95s; }
    
    .map-container {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
        animation: slideInScale 0.8s ease-out 0.8s both;
    }
    
    .submit-button {
        opacity: 0;
        transform: translateY(20px);
        animation: bounceInUp 0.8s ease-out 1.2s both;
    }
    
    /* Keyframe Definitions */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInScale {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes bounceInUp {
        0% {
            opacity: 0;
            transform: translateY(50px) scale(0.8);
        }
        60% {
            opacity: 1;
            transform: translateY(-10px) scale(1.05);
        }
        80% {
            transform: translateY(5px) scale(0.98);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.5s ease-out;
    }
    
    /* Interactive Hover Animations */
    .form-input, .form-select {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .form-input:hover, .form-select:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .form-input:focus, .form-select:focus {
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    }
    
    /* Enhanced Button Animation */
    .submit-button {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .submit-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease-out;
    }
    
    .submit-button:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 30px rgba(19, 57, 93, 0.3);
    }
    
    .submit-button:hover::before {
        left: 100%;
    }
    
    .submit-button:active {
        transform: translateY(-1px) scale(0.98);
        transition: all 0.1s ease-out;
    }

    /* Switch Styles with Animation */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        opacity: 0;
        animation: slideInLeft 0.6s ease-out 0.9s both;
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
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
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
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-radius: 50%;
    }

    .switch input:checked + .slider {
        background-color: #2196F3;
    }

    .switch input:checked + .slider:before {
        transform: translateX(20px);
    }

    .switch:hover .slider {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transform: scale(1.05);
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
        transition: box-shadow 0.3s ease;
    }
    
    .form-wrapper:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
    }

    .form-title {
        padding-top: 0.5rem !important;
        padding-bottom: 1rem !important;
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
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
        transition: color 0.2s ease;
    }

    .form-input, .form-select {
        width: 100% !important;
        padding: 0.625rem !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        font-size: 0.875rem !important;
        background: white !important;
        color: #374151 !important;
    }

    .form-input:focus, .form-select:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
    }

    .form-input:focus + .form-label,
    .form-select:focus + .form-label {
        color: #3b82f6 !important;
    }

    .form-input::placeholder {
        color: #9ca3af !important;
        font-weight: 400 !important;
        transition: color 0.2s ease;
    }

    .form-input:focus::placeholder {
        color: transparent !important;
    }

    .submit-button {
        background: #13395d !important;
        color: white !important;
        border: 2px solid #fbbf0f !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        font-size: 0.875rem !important;
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
        min-width: 120px !important;
        cursor: pointer;
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
        
        /* Adjust animation timing for mobile */
        .form-group {
            animation-delay: 0.2s !important;
        }
    }

    /* Address Suggestions Styles */
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
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e5e7eb !important;
        animation: slideInScale 0.3s ease-out;
    }
    
    .address-suggestion {
        padding: 8px 12px !important;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
        background-color: white;
        transition: all 0.2s ease !important;
        font-size: 0.875rem !important;
    }
    
    .address-suggestion:hover {
        background-color: #f8fafc !important;
        transform: translateX(8px);
        padding-left: 16px !important;
    }
    
    .address-suggestion:last-child {
        border-bottom: none;
        border-radius: 0 0 8px 8px !important;
    }
    
    .address-suggestion:first-child {
        border-radius: 8px 8px 0 0 !important;
    }

    /* Leaflet Controls */
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
        transition: all 0.3s ease;
    }
    
    #map:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
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
        opacity: 0;
        animation: slideInScale 0.5s ease-out 0.2s both;
    }

    /* Static IP Fields Animation */
    #static-ip-fields {
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-origin: top;
    }
    
    #static-ip-fields.show {
        animation: expandDown 0.4s ease-out;
    }
    
    @keyframes expandDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: scaleY(0);
        }
        to {
            opacity: 1;
            max-height: 200px;
            transform: scaleY(1);
        }
    }
</style>

<!-- Leaflet CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="page-container form-container flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="max-w-4xl w-full px-4 py-8">
        <div class="form-wrapper">
            <h1 class="form-title text-center">Create Network Provisioning</h1>

            <form method="POST" action="{{ route('network-provisioning.store') }}" class="space-y-6">
                @csrf

                <!-- Row 1: Property Name / Property Type -->
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

                <!-- Row 2: Property Address -->
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
                
                <div class="map-container">
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

                <!-- Row 3: Master Unit Quantity / BDA Quantity -->
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

                <!-- Row 4: Latitude / Longitude -->
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

                <!-- Row 5: Average Density / Remote Unit Quantity -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label">Average Density</label>
                        <select name="average_density" class="form-select">
                            <option value="">Select the density</option>
                            <option value="Low" {{ old('average_density') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('average_density') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('average_density') == 'High' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Remote Unit Quantity</label>
                        <input type="number" name="remote_unit_quantity" value="{{ old('remote_unit_quantity') }}"
                               class="form-input" placeholder="Type the quantity">
                    </div>
                </div>

                <!-- Row 6: Hostname / Static IP Toggle -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label" for="hostname">Hostname (dyndns)</label>
                        <input class="form-input" type="text" id="hostname" name="hostname" value="{{ old('hostname') }}">
                    </div>
                    <div class="form-group">
                        <div class="flex items-center pt-6">
                            <label class="form-label mr-3 mb-0" for="static_ip_check">
                                Static IP
                            </label>
                            <label class="switch ml-2">
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
                        @foreach>
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
    
    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupComponents());
        } else {
            setTimeout(() => this.setupComponents(), 100);
        }
    }
    
    setupComponents() {
        if (typeof L === 'undefined') {
            console.error('Leaflet not loaded. Trying again...');
            setTimeout(() => this.setupComponents(), 200);
            return;
        }
        
        console.log('Initializing OpenStreetMap Handler...');
        this.initMap();
        this.setupEventListeners();
    }
    
    initMap(lat = 40.7589, lng = -73.9851, zoom = 10) {
        try {
            const mapElement = document.getElementById(this.mapElementId);
            if (!mapElement) {
                console.error('Map element not found:', this.mapElementId);
                return;
            }
            
            console.log('Initializing map...');
            this.map = L.map(this.mapElementId).setView([lat, lng], zoom);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(this.map);
            
            console.log('Map initialized successfully!');
            
            this.map.on('click', (e) => {
                this.addMarker(e.latlng.lat, e.latlng.lng);
                this.updateCoordinateFields(e.latlng.lat, e.latlng.lng);
            });
            
            setTimeout(() => {
                this.map.invalidateSize();
            }, 250);
            
        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }
    
    setupEventListeners() {
        const addressInput = document.getElementById(this.addressInputId);
        const suggestionsDiv = document.getElementById(this.suggestionsId);
        
        if (!addressInput || !suggestionsDiv) {
            console.error('Elements not found. Check HTML element IDs.');
            return;
        }
        
        console.log('Setting up event listeners...');
        
        // Address autocomplete
        addressInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(async () => {
                if (query.length >= 3) {
                    console.log('Searching addresses for:', query);
                    const suggestions = await this.searchAddresses(query);
                    this.showAddressSuggestions(suggestions);
                } else {
                    suggestionsDiv.classList.add('hidden');
                }
            }, 500);
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!addressInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
        
        console.log('Event listeners configured!');
    }
    
    // Search addresses using Nominatim
    async searchAddresses(query) {
        if (query.length < 3) return [];
        
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`;
            console.log('Search URL:', url);
            
            const response = await fetch(url, {
                headers: {
                    'User-Agent': 'NetworkProvisioningApp/1.0 (Laravel Application)'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Search results:', data.length, 'addresses found');
            return data;
            
        } catch (error) {
            console.error('Error searching addresses:', error);
            return [];
        }
    }
    
    // Show address suggestions
    showAddressSuggestions(suggestions) {
        const suggestionsDiv = document.getElementById(this.suggestionsId);
        
        if (suggestions.length === 0) {
            suggestionsDiv.classList.add('hidden');
            console.log('No suggestions found');
            return;
        }
        
        console.log('Showing', suggestions.length, 'suggestions');
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
    
    // Select an address
    selectAddress(address) {
        console.log('Address selected:', address.display_name);
        
        const input = document.getElementById(this.addressInputId);
        input.value = address.display_name;
        this.selectedAddressText = address.display_name;
        
        // Hide suggestions
        document.getElementById(this.suggestionsId).classList.add('hidden');
        
        // Update map
        const lat = parseFloat(address.lat);
        const lng = parseFloat(address.lon);
        
        this.selectedCoordinates = { lat, lng };
        
        // Update coordinate fields
        this.updateCoordinateFields(lat, lng);
        
        // Add marker and center map
        this.addMarker(lat, lng);
        this.map.setView([lat, lng], 15);
    }
    
    // Add marker on map
    addMarker(lat, lng) {
        // Remove previous marker if exists
        if (this.marker) {
            this.map.removeLayer(this.marker);
        }
        
        // Add new marker
        this.marker = L.marker([lat, lng]).addTo(this.map);
        console.log('Marker added at:', lat, lng);
    }
    
    // Update coordinate fields
    updateCoordinateFields(lat, lng) {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        
        if (latInput && lngInput) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            console.log('Coordinates updated:', lat.toFixed(6), lng.toFixed(6));
        }
    }
    
    // Public methods to access data
    getSelectedCoordinates() {
        return this.selectedCoordinates;
    }
    
    getSelectedAddress() {
        return this.selectedAddressText;
    }
    
    // Method to set custom callback
    setAddressSelectedCallback(callback) {
        this.onAddressSelected = callback;
    }
    
    // Method to set initial map position
    setInitialPosition(lat, lng, zoom = 10) {
        if (this.map) {
            this.map.setView([lat, lng], zoom);
        }
    }
}

// Function to toggle static IP fields
function toggleStaticIpFields() {
    var checkbox = document.getElementById('static_ip_check');
    var fields = document.getElementById('static-ip-fields');
    
    if (checkbox.checked) {
        fields.style.display = 'block';
        fields.classList.add('show');
    } else {
        fields.style.display = 'none';
        fields.classList.remove('show');
    }
}

// Initialize map when page is ready
let mapHandler;

// Wait for both DOM and Leaflet
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure all resources are loaded
    setTimeout(() => {
        mapHandler = new OpenStreetMapHandler();
    }, 100);
    
    // Add some additional interactive enhancements
    addFormInteractivity();
});

// Additional form interactivity
function addFormInteractivity() {
    // Add focus animations to form inputs
    const inputs = document.querySelectorAll('.form-input, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.2s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
    
    // Add ripple effect to submit button
    const submitButton = document.querySelector('.submit-button');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    }
}