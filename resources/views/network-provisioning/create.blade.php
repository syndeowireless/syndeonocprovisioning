@extends('layouts.app')

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

@section("content")
@if(session("success"))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session("success") }}
    </div>
@endif
<style>
/* Simple Title Styles */
.title-container {
    text-align: left;
}

.simple-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #13395d;
    margin-bottom: 0;
}

/* Scoped utility to avoid clashing with Bootstrap's responsive .d-none/.d-lg-* */
.is-hidden {
    display: none !important;
}

    .switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.loading-overlay {
    position: fixed;
    background-color: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}

/* Ensure the main content area is properly positioned for overlay */
.main-content {
    position: relative;
    z-index: 1;
}

/* Ensure sidebar and topbar stay above overlay when needed */
.vertical-menu {
    z-index: 10000 !important;
}

.navbar-header {
    z-index: 10000 !important;
}

/* Loading animation container */
.loading-container {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: 0 auto;
}

/* Loading GIF styles */
.loading-gif {
    max-width: 280px;
    max-height: 280px;
    width: auto;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    margin-bottom: 1.5rem;
    display: block;
}

/* Loading text styles */
.loading-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #2B2B22;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.loading-subtitle {
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    color: #6b7280;
    font-weight: 400;
    line-height: 1.4;
    max-width: 300px;
    margin: 0 auto;
}

/* Fallback spinner styles */
.loading-spinner {
    width: 60px;
    height: 60px;
    border: 6px solid #e5e7eb;
    border-top: 6px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1.5rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments for loading overlay */
@media (max-width: 992px) {
    .loading-overlay {
        left: 0 !important;
        top: 60px !important; /* Adjust for mobile topbar */
    }
    
    .loading-container {
        max-width: 90%;
        padding: 1.5rem;
    }
    
    .loading-gif {
        max-width: 220px;
        max-height: 220px;
    }
    
    .loading-title {
        font-size: 1.25rem;
    }
    
    .loading-subtitle {
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .loading-overlay {
        top: 50px !important;
    }
    
    .loading-container {
        padding: 1rem;
    }
    
    .loading-gif {
        max-width: 180px;
        max-height: 180px;
    }
    
    .loading-title {
        font-size: 1.125rem;
    }
}

/* Ensure proper stacking context */
body.loading-active {
    overflow: hidden; /* Prevent scrolling during loading */
}

/* Animation for loading overlay entrance */
.loading-overlay.fade-in {
    animation: fadeInOverlay 0.3s ease forwards;
}

.loading-overlay.fade-out {
    animation: fadeOutOverlay 0.3s ease forwards;
}

@keyframes fadeInOverlay {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fadeOutOverlay {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.95);
    }
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

/* Required field asterisk styling */
.required::after {
    content: ' *' !important;
    color: #dc2626 !important;
    font-weight: 600 !important;
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
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
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

/* Error state for required fields */
.form-input.error, .form-select.error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.1) !important;
}

/* Enhanced select elements with smooth animations */
.form-select {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
    background-position: right 0.5rem center !important;
    background-repeat: no-repeat !important;
    background-size: 1.5em 1.5em !important;
    padding-right: 2.5rem !important;
    
    /* Enhanced smooth transition for dropdown interactions */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
    overflow: hidden !important;
}

/* Smooth hover effect for better UX */
.form-select:hover:not(:focus) {
    border-color: #9ca3af !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
}

/* Enhanced focus state with smooth glow animation */
.form-select:focus {
    outline: none !important;
    border-color: #3b82f6 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
    animation: focusGlow 0.4s ease-in-out !important;
    transform: translateY(0) !important;
}

/* Subtle click animation for better feedback */
.form-select:active {
    transform: translateY(0) scale(0.99) !important;
    transition: all 0.1s ease !important;
}

/* Smooth glow animation for focus state */
@keyframes focusGlow {
    0% {
        box-shadow: 0 0 0 0px rgba(59, 130, 246, 0) !important;
    }
    50% {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important;
    }
    100% {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
    }
}

/* Dropdown opening animation simulation */
.form-select:focus + .dropdown-options,
.form-select[data-dropdown-open="true"] {
    animation: dropdownSlideIn 0.25s ease-out !important;
}

@keyframes dropdownSlideIn {
    0% {
        opacity: 0 !important;
        transform: translateY(-8px) scale(0.95) !important;
    }
    100% {
        opacity: 1 !important;
        transform: translateY(0) scale(1) !important;
    }
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

/* Toast notification styles */
.toast {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    background: #dc2626 !important;
    color: white !important;
    padding: 1rem 1.5rem !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    z-index: 10000 !important;
    opacity: 0 !important;
    transform: translateX(100%) !important;
    transition: all 0.3s ease !important;
    max-width: 400px !important;
    font-weight: 500 !important;
}

.toast.show {
    opacity: 1 !important;
    transform: translateX(0) !important;
}

.toast.success {
    background: #059669 !important;
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

    .toast {
        top: 10px !important;
        right: 10px !important;
        left: 10px !important;
        max-width: none !important;
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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="title-container mb-4">
                    <h1 class="simple-title mb-3">Create Network Provisioning</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-container flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="max-w-4xl w-full px-4 py-8">
        <div class="form-wrapper">

            <form method="POST" action="{{ route('network-provisioning.store') }}" class="space-y-6" id="networkProvisioningForm">
                @csrf

                <!-- Linha 1: Property Name / Property Type -->
                <div class="grid-container">
                    <div class="form-group">
                        <label class="form-label required">Property Name</label>
                        <input type="text" name="property_name" id="property_name" value="{{ old('property_name') }}" required
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
                        <label class="form-label required">Property Address</label>
                        <div class="address-input-container">
                            <input type="text" name="property_address" id="property_address" value="{{ old('property_address') }}" required
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
                        <label class="form-label required">System Type</label>
                        <select name="system_type" id="system_type" class="form-select" required>
                            <option value="">Select the system type</option>
                            <option value="DAS" {{ old('system_type') == 'DAS' ? 'selected' : '' }}>DAS</option>
                            <option value="ERRCS" {{ old('system_type') == 'ERRCS' ? 'selected' : '' }}>ERRCS</option>
                            <option value="DAS & ERRCS" {{ old('system_type') == 'DAS & ERRCS' ? 'selected' : '' }}>DAS & ERRCS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">OEM</label>
                        <select name="oem" id="oem" class="form-select" required>
                            <option value="">Select the OEM</option>
                            <option value="ADRF" {{ old('oem') == 'ADRF' ? 'selected' : '' }}>ADRF</option>
                            <option value="COMBA" {{ old('oem') == 'COMBA' ? 'selected' : '' }}>COMBA</option>
                            <option value="CommScope" {{ old('oem') == 'CommScope' ? 'selected' : '' }}>CommScope</option>
                            <option value="JMA" {{ old('oem') == 'JMA' ? 'selected' : '' }}>JMA</option>
                            <option value="SOLID" {{ old('oem') == 'SOLID' ? 'selected' : '' }}>SOLID</option>
                        </select>                        
                    </div>
                </div>
                <!-- Linha 3: Master Unit Quantity / BDA Quantity -->
                <div class="grid-container">
                    <div class="form-group" id="master_unit_quantity_container">
                        <label class="form-label required">Master Unit Quantity</label>
                        <input type="number" name="master_unit_quantity" id="master_unit_quantity" value="{{ old('master_unit_quantity') }}" required
                               class="form-input" placeholder="Type the quantity" min="0">
                    </div>
                    <div class="form-group" id="bda_quantity_container">
                        <label class="form-label required">BDA Unit Quantity</label>
                        <input type="number" name="bda_quantity" id="bda_quantity" value="{{ old('bda_quantity') }}" required
                               class="form-input" placeholder="Type the quantity" min="0">
                    </div>
                </div>

                <!-- Equipment fields - will be dynamically positioned based on system type -->
                <div class="grid-container" id="equipment_fields_container">
                    <!-- Master Unit Equipment for DAS -->
                    <div id="das_equipment_container" class="form-group" style="display:none;">
                        <label class="form-label">Master Unit Equipment</label>
                        <select name="das_equipment" id="das_equipment" class="form-select" required>
                            <option value="">Select the system type</option>
                            <option value="Syndeo V1.0 202505 JMA WIRELESS TEKO OMT DAS" {{ old('das_equipment') == 'Syndeo V1.0 202505 JMA WIRELESS TEKO OMT DAS' ? 'selected' : '' }}>Syndeo V1.0 202505 JMA WIRELESS TEKO OMT DAS</option>
                            <option value="Syndeo V1.0  ADRF 202505 DAS" {{ old('das_equipment') == 'Syndeo V1.0  ADRF 202505 DAS' ? 'selected' : '' }}>Syndeo V1.0 ADRF 202505 DAS</option>
                            <option value="Syndeo V1.0 COMBA 202505 DAS LLD" {{ old('das_equipment') == 'Syndeo V1.0 COMBA 202505 DAS LLD' ? 'selected' : '' }}>Syndeo V1.0 COMBA 202505 DAS LLD</option>
                            <option value="Syndeo V1.0 COMMSCOPE DAS LLD" {{ old('das_equipment') == 'Syndeo V1.0 COMMSCOPE DAS LLD' ? 'selected' : '' }}>Syndeo V1.0 COMMSCOPE DAS LLD</option>
                            <option value="Syndeo V1.0 SOLID DMS1200 DAS LLD" {{ old('das_equipment') == 'Syndeo V1.0 SOLID DMS1200 DAS LLD' ? 'selected' : '' }}>Syndeo V1.0 SOLID DMS1200 DAS LLD</option>
                            <option value="Syndeo V1.0 202505 COMMSCOPE" {{ old('das_equipment') == 'Syndeo V1.0 202505 COMMSCOPE' ? 'selected' : '' }}>Syndeo V1.0 202505 COMMSCOPE</option>
                            <option value="Syndeo V1.0 COMBA 202505 Model 2014" {{ old('das_equipment') == 'Syndeo V1.0 COMBA 202505 Model 2014' ? 'selected' : '' }}>Syndeo V1.0 COMBA 202505 Model 2014</option>
                        </select>
                    </div>
                    
                    <!-- BDA Unit Equipment for ERRCS - will toggle with Master Unit Equipment -->
                    <div id="errcs_equipment_container" class="form-group" style="display:none;">
                        <label class="form-label">BDA Unit Equipment</label>
                        <select name="errcs_equipment" id="errcs_equipment" class="form-select" required>
                            <option value="">Select the BDA equipment</option>
                            <option value="Syndeo V1.0  ADRF 202505 SDR" {{ old('errcs_equipment') == 'Syndeo V1.0  ADRF 202505 SDR' ? 'selected' : '' }}>Syndeo V1.0 ADRF 202505 SDR</option>
                            <option value="Syndeo V1.0 COMBA 202505 RX7W22 CLASSB" {{ old('errcs_equipment') == 'Syndeo V1.0 COMBA 202505 RX7W22 CLASSB' ? 'selected' : '' }}>Syndeo V1.0 COMBA 202505 RX7W22 CLASSB</option>
                            <option value="Syndeo V1.0 COMBA RX7W22 CLASS A LLD ERRCS" {{ old('errcs_equipment') == 'Syndeo V1.0 COMBA RX7W22 CLASS A LLD ERRCS' ? 'selected' : '' }}>Syndeo V1.0 COMBA RX7W22 CLASS A LLD ERRCS</option>
                        </select>
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
        <label class="form-label required" for="hostname">Hostname (dyndns)</label>
        <input class="form-input" type="text" id="hostname" name="hostname" value="{{ old('hostname') }}" required
               placeholder="Type the hostname">
    </div>

    <!-- Create Grafana Credentials toggle now comes in place of Remote Unit Quantity -->
    <div class="form-group">
        <label class="form-label">Remote unit</label>
        <input class="form-input" type="number" id="remote_unit_quantity" name="remote_unit_quantity" value="{{ old('remote_unit_quantity') }}" required
               placeholder="Type the Remote Unit">
    </div>

</div>

<!-- Conditional Customer Email field (hidden until toggle is yes) -->
<div id="grafana-email-field" class="{{ old('grafana_toggle') ? '' : 'is-hidden' }}">
    <div class="grid-container">
        <div class="form-group">
            <label class="form-label" for="company_name">Company Name</label>
            <input class="form-input" type="text" id="company_name" name="company_name"
                   value="{{ old('company_name') }}" placeholder="Enter the Company Name">
        </div>
        <div class="form-group">
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

<div id="static-ip-fields" class="{{ old('static_ip_check') ? '' : 'is-hidden' }}">
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
    <div class="grid-container">
            <div class="form-group">
                <label class="form-label" for="static_ip">Gateway</label>
                <input class="form-input" type="text" id="static_gateway" name="static_gateway"
                   value="{{ old('static_gateway') }}" placeholder="Type the Gateway">
        </div> 
    </div>
</div>

                <!-- Submit Button -->
                <div class="text-center">
                <x-primary-button type="submit">
                    CREATE
                </x-primary-button>
                </div>
                <script>
function playLoadingAnimation(event) {
    // Prevent the default form submission
    event.preventDefault();
    
    // First validate the form
    if (!validateRequiredFields()) {
        return false;
    }
    
    // Get the form reference
    const form = document.getElementById('networkProvisioningForm');
    
    // Create overlay that only covers the main content area (not sidebar/topbar)
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 70px; /* Height of topbar */
        left: 240px; /* Width of sidebar */
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.3s ease;
        opacity: 0;
    `;
    
    // Adjust overlay position for mobile screens
    if (window.innerWidth <= 992) {
        overlay.style.left = '0px'; // Full width on mobile
        overlay.style.top = '60px'; // Adjust for mobile topbar height
    }
    
    // Create container for the loading content
    const loadingContainer = document.createElement('div');
    loadingContainer.style.cssText = `
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    `;
    
    // Create GIF element
    const gifElement = document.createElement('img');
    gifElement.src = '/assets/images/Transition_Animation.gif';
    gifElement.alt = 'Loading...';
    gifElement.style.cssText = `
        max-width: 300px;
        max-height: 300px;
        width: auto;
        height: auto;
        margin-bottom: 1.5rem;
    `;
    
    // Create loading text
    const loadingText = document.createElement('div');
    loadingText.innerHTML = `
        <div style="
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #2B2B22;
            margin-bottom: 0.5rem;
        "></div>
        <div style="
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            color: #6b7280;
            font-weight: 400;
        "></div>
    `;
    
    // Create fallback loading spinner (in case GIF fails to load)
    const spinner = document.createElement('div');
    spinner.style.cssText = `
        width: 60px;
        height: 60px;
        border: 6px solid #e5e7eb;
        border-top: 6px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1.5rem;
        display: none;
    `;
    
    // Add CSS for spinner animation
    if (!document.querySelector('#loading-spinner-style')) {
        const style = document.createElement('style');
        style.id = 'loading-spinner-style';
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Assemble the loading container
    loadingContainer.appendChild(gifElement);
    loadingContainer.appendChild(spinner);
    loadingContainer.appendChild(loadingText);
    overlay.appendChild(loadingContainer);
    
    // Disable the submit button and show loading state
    const submitButton = event.target;
    const originalText = submitButton.textContent;
    submitButton.textContent = 'PROCESSING...';
    submitButton.disabled = true;
    
    // Function to submit form after animation
    function submitForm() {
        console.log('Submitting form after animation...');
        try { sessionStorage.setItem('showTransitionOverlay', '1'); } catch (_) {}
        form.submit();
    }
    
    // Handle GIF loading
    let animationStarted = false;
    let animationDuration = 3000; // Default 3 seconds if we can't detect GIF duration
    
    gifElement.addEventListener('load', () => {
        console.log('GIF loaded successfully');
        if (!animationStarted) {
            animationStarted = true;
            // Start the animation timer
            setTimeout(submitForm, animationDuration);
        }
    });
    
    gifElement.addEventListener('error', () => {
        console.log('GIF failed to load, showing spinner instead');
        gifElement.style.display = 'none';
        spinner.style.display = 'block';
        if (!animationStarted) {
            animationStarted = true;
            setTimeout(submitForm, 2000); // Shorter duration for spinner
        }
    });
    
    // Add overlay to the main content area
    document.body.appendChild(overlay);
    
    // Fade in the overlay
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
    });
    
    // Handle window resize to adjust overlay position
    const handleResize = () => {
        if (window.innerWidth <= 992) {
            overlay.style.left = '0px';
            overlay.style.top = '60px';
        } else {
            overlay.style.left = '240px';
            overlay.style.top = '70px';
        }
    };
    
    window.addEventListener('resize', handleResize);
    
    // Timeout fallback (in case something goes wrong)
    setTimeout(() => {
        console.log('Timeout reached, submitting form...');
        if (!animationStarted) {
            animationStarted = true;
            submitForm();
        }
    }, 8000); // 8 second maximum timeout
    
    // Clean up function
    const cleanup = () => {
        window.removeEventListener('resize', handleResize);
        if (overlay && overlay.parentNode) {
            overlay.style.opacity = '0';
            setTimeout(() => {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, 300);
        }
    };
    
    // Store cleanup function for potential use
    window.cleanupLoadingAnimation = cleanup;
    
    return false; // Prevent default form submission
}

// Update the DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('networkProvisioningForm');
    const submitButton = form.querySelector('.submit-button');
    
    if (submitButton) {
        // Remove any existing event listeners
        submitButton.removeEventListener('click', playLoadingAnimation);
        
        // Add the updated event listener
        submitButton.addEventListener('click', function(event) {
            playLoadingAnimation(event);
        });
    }
    
    // Handle potential navigation away from page
    window.addEventListener('beforeunload', function() {
        if (window.cleanupLoadingAnimation) {
            window.cleanupLoadingAnimation();
        }
    });
});
</script>
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

// Toast notification function
function showToast(message, type = 'error') {
    // Remove existing toast if any
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'error' ? 
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>' :
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    // Add toast to DOM
    document.body.appendChild(toast);
    
    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto-hide toast after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast && toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

// REPLACE the existing validateRequiredFields() function with this:

function validateRequiredFields() {
    const systemType = document.getElementById('system_type').value;
    
    // Base required fields that are always required
    const baseRequiredFields = [
        { id: 'property_name', name: 'Property Name' },
        { id: 'property_address', name: 'Property Address' },
        { id: 'system_type', name: 'System Type' },
        { id: 'oem', name: 'OEM' },
        { id: 'hostname', name: 'Hostname (dyndns)' }
    ];
    
    // Add conditional required fields based on system type
    let requiredFields = [...baseRequiredFields];
    
    if (systemType === 'DAS') {
        requiredFields.push({ id: 'master_unit_quantity', name: 'Master Unit Quantity' });
    } else if (systemType === 'ERRCS') {
        requiredFields.push({ id: 'bda_quantity', name: 'BDA Quantity' });
    } else if (systemType === 'DAS & ERRCS') {
        requiredFields.push(
            { id: 'master_unit_quantity', name: 'Master Unit Quantity' },
            { id: 'bda_quantity', name: 'BDA Quantity' }
        );
    } else {
        // If no system type selected, require both for validation message
        requiredFields.push(
            { id: 'master_unit_quantity', name: 'Master Unit Quantity' },
            { id: 'bda_quantity', name: 'BDA Quantity' }
        );
    }
    
    let emptyFields = [];
    let hasErrors = false;
    
    // Reset all error states
    document.querySelectorAll('.form-input, .form-select').forEach(element => {
        element.classList.remove('error');
    });
    
    // Check each required field
    requiredFields.forEach(field => {
        const element = document.getElementById(field.id);
        if (element && !element.disabled) { // Only validate if field is not disabled
            const value = element.value.trim();
            // For quantity fields, allow 0 as a valid value
            if (field.id === 'master_unit_quantity' || field.id === 'bda_quantity') {
                if (value === '' || isNaN(parseInt(value)) || parseInt(value) < 0) {
                    emptyFields.push(field.name);
                    element.classList.add('error');
                    hasErrors = true;
                }
            } else {
                if (!value) {
                    emptyFields.push(field.name);
                    element.classList.add('error');
                    hasErrors = true;
                }
            }
        }
    });
    
    if (hasErrors) {
        const message = emptyFields.length === 1 
            ? `Please fill in the ${emptyFields[0]} field.`
            : `Please fill in all required fields: ${emptyFields.join(', ')}.`;
        
        showToast(message, 'error');
        
        // Focus on the first empty field that's not disabled
        const firstEmptyField = requiredFields.find(field => {
            const element = document.getElementById(field.id);
            return element && !element.disabled && !element.value.trim();
        });
        
        if (firstEmptyField) {
            const element = document.getElementById(firstEmptyField.id);
            if (element) {
                element.focus();
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        return false;
    }
    
    return true;
}
function handleFormSubmission(event) {
    if (!validateRequiredFields()) {
        event.preventDefault();
        return false;
    }
    
    // If validation passes, show success message
    showToast('Form is being submitted...', 'success');
    return true;
}

// Função para toggle dos campos de IP estático
//function toggleStaticIpFields() {
//    var checkbox = document.getElementById('static_ip_check');
//    var fields = document.getElementById('static-ip-fields');
//    fields.style.display = checkbox.checked ? 'block' : 'none';
//}

function toggleStaticIpFields() {
    var checkbox = document.getElementById('static_ip_check');
    var fields = document.getElementById('static-ip-fields');
    if (checkbox.checked) {
        fields.classList.remove('is-hidden');
    } else {
        fields.classList.add('is-hidden');
    }
}

//function toggleGrafanaEmail() {
//    const toggle = document.getElementById('grafana_toggle');
//    const emailField = document.getElementById('grafana-email-field');
//    emailField.style.display = toggle.checked ? 'block' : 'none';
//}

function toggleGrafanaEmail() {
    const toggle = document.getElementById('grafana_toggle');
    const emailField = document.getElementById('grafana-email-field');
    if (toggle.checked) {
        emailField.classList.remove('is-hidden');
    } else {
        emailField.classList.add('is-hidden');
    }
}

// Inicializar o mapa quando a página estiver pronta
let mapHandler;

// Aguardar tanto o DOM quanto o Leaflet
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation
    const form = document.getElementById('networkProvisioningForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmission);
    }
    
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

</script>

<script>
// REPLACE the existing updateFields script with this:

document.addEventListener('DOMContentLoaded', function () {
    function updateOEMOptions() {
        const type = document.getElementById('system_type').value;
        const oemSelect = document.getElementById('oem');
        const currentValue = oemSelect.value;
        
        // Clear all options except the first one
        oemSelect.innerHTML = '<option value="">Select the OEM</option>';
        
        if (type === 'ERRCS' || type === 'DAS & ERRCS') {
            // For ERRCS and DAS & ERRCS, only show ADRF and COMBA
            const adrfOption = document.createElement('option');
            adrfOption.value = 'ADRF';
            adrfOption.textContent = 'ADRF';
            if (currentValue === 'ADRF') adrfOption.selected = true;
            oemSelect.appendChild(adrfOption);
            
            const combaOption = document.createElement('option');
            combaOption.value = 'COMBA';
            combaOption.textContent = 'COMBA';
            if (currentValue === 'COMBA') combaOption.selected = true;
            oemSelect.appendChild(combaOption);
        } else {
            // For other system types, show all OEMs
            const oems = [
                { value: 'ADRF', text: 'ADRF' },
                { value: 'COMBA', text: 'COMBA' },
                { value: 'CommScope', text: 'CommScope' },
                { value: 'JMA', text: 'JMA' },
                { value: 'SOLID', text: 'SOLID' }
            ];
            
            oems.forEach(oem => {
                const option = document.createElement('option');
                option.value = oem.value;
                option.textContent = oem.text;
                if (currentValue === oem.value) option.selected = true;
                oemSelect.appendChild(option);
            });
        }
    }
    
    function updateFields() {
        const type = document.getElementById('system_type').value;
        const oem = document.getElementById('oem').value;
        const master = document.getElementById('master_unit_quantity');
        const bda = document.getElementById('bda_quantity');
        const masterContainer = document.getElementById('master_unit_quantity_container');
        const bdaContainer = document.getElementById('bda_quantity_container');
        const dasEquipmentContainer = document.getElementById('das_equipment_container');
        const errcsEquipmentContainer = document.getElementById('errcs_equipment_container');
        const equipmentFieldsContainer = document.getElementById('equipment_fields_container');
        
        // Remove any existing error states when fields change
        master.classList.remove('error');
        bda.classList.remove('error');
        
        // Reset layout to default
        masterContainer.style.display = '';
        bdaContainer.style.display = '';
        dasEquipmentContainer.style.display = 'none';
        errcsEquipmentContainer.style.display = 'none';
        errcsEquipmentContainer.style.visibility = 'hidden';
        
        // Reset equipment container to default position
        if (equipmentFieldsContainer && equipmentFieldsContainer.parentElement) {
            // Move equipment container back to its original position if it was moved
            const originalParent = document.querySelector('.form-wrapper');
            if (originalParent && !originalParent.contains(equipmentFieldsContainer)) {
                // Find the position after the quantity fields
                const quantityGrid = bdaContainer.parentElement;
                if (quantityGrid && quantityGrid.nextSibling) {
                    quantityGrid.parentElement.insertBefore(equipmentFieldsContainer, quantityGrid.nextSibling);
                } else {
                    originalParent.appendChild(equipmentFieldsContainer);
                }
            }
        }
        
        if (type === 'DAS') {
            master.disabled = false;
            master.required = true;
            bda.disabled = true;
            bda.required = false;
            bda.value = '';
            
            // Hide BDA Quantity completely for DAS
            bdaContainer.style.display = 'none';
            
            // Show Master Unit Equipment for DAS system type
            // Move Master Unit Equipment to BDA Quantity's position
            const bdaParentGrid = bdaContainer.parentElement;
            if (bdaParentGrid && !bdaParentGrid.contains(dasEquipmentContainer)) {
                bdaParentGrid.appendChild(dasEquipmentContainer);
            }
            dasEquipmentContainer.style.display = '';
            
            // Hide BDA Equipment completely
            errcsEquipmentContainer.style.display = 'none';
            errcsEquipmentContainer.style.visibility = 'hidden';
            
            master.style.backgroundColor = '';
            master.style.cursor = '';
        } else if (type === 'ERRCS') {
            // For ERRCS: Hide Master Unit fields, show BDA fields
            masterContainer.style.display = 'none';
            bdaContainer.style.display = '';
            
            master.disabled = true;
            master.required = false;
            master.value = '';
            bda.disabled = false;
            bda.required = true;
            
            bda.style.backgroundColor = '';
            bda.style.cursor = '';
            
            // For ERRCS: Show BDA Unit Equipment side by side with BDA Unit Quantity
            // Move BDA Unit Equipment to the same row as BDA Unit Quantity
            const bdaParentGrid = bdaContainer.parentElement;
            if (bdaParentGrid && !bdaParentGrid.contains(errcsEquipmentContainer)) {
                bdaParentGrid.appendChild(errcsEquipmentContainer);
            }
            errcsEquipmentContainer.style.display = 'block';
            errcsEquipmentContainer.style.visibility = 'visible';
            
            // Hide Master Unit Equipment
            dasEquipmentContainer.style.display = 'none';
        } else if (type === 'DAS & ERRCS') {
            master.disabled = false;
            master.required = true;
            bda.disabled = false;
            bda.required = true;
            // Both fields enabled
            master.style.backgroundColor = '';
            master.style.cursor = '';
            bda.style.backgroundColor = '';
            bda.style.cursor = '';
            
            // For DAS & ERRCS: Show Master Unit Equipment and BDA Unit Equipment side by side
            // Move both equipment containers to the same row
            const bdaParentGrid = bdaContainer.parentElement;
            if (bdaParentGrid && !bdaParentGrid.contains(dasEquipmentContainer)) {
                bdaParentGrid.appendChild(dasEquipmentContainer);
            }
            if (bdaParentGrid && !bdaParentGrid.contains(errcsEquipmentContainer)) {
                bdaParentGrid.appendChild(errcsEquipmentContainer);
            }
            
            // Show both equipment containers
            dasEquipmentContainer.style.display = 'block';
            errcsEquipmentContainer.style.display = 'block';
            errcsEquipmentContainer.style.visibility = 'visible';
        } else {
            // No system type selected - enable both but don't require
            master.disabled = false;
            master.required = false;
            bda.disabled = false;
            bda.required = false;
            master.style.backgroundColor = '';
            master.style.cursor = '';
            bda.style.backgroundColor = '';
            bda.style.cursor = '';
            
            // Reset BDA Equipment visibility
            errcsEquipmentContainer.style.visibility = 'visible';
        }
        
        // Update OEM options based on system type
        updateOEMOptions();
    }
    
    // Forward declaration
    let updateEquipmentFields;
    
    const systemTypeSelect = document.getElementById('system_type');
    const oemSelect = document.getElementById('oem');
    if (systemTypeSelect) {
        systemTypeSelect.addEventListener('change', function() {
            updateFields();
            if (updateEquipmentFields) updateEquipmentFields();
        });
        updateFields(); // Initial call to handle default value
    }
    if (oemSelect) {
        oemSelect.addEventListener('change', function() {
            updateFields();
            if (updateEquipmentFields) updateEquipmentFields();
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Equipment model configurations based on System Type and OEM combinations
    const equipmentConfig = {
        'DAS': {
            'CommScope': {
                masterUnit: ['Syndeo V1.0 202505 COMMSCOPE'],
                bdaEquipment: [],
                showBDA: false
            },
            'JMA': {
                masterUnit: ['Syndeo V1.0 202505 JMA WIRELESS TEKO OMT DAS'],
                bdaEquipment: [],
                showBDA: false
            },
            'SOLID': {
                masterUnit: ['Syndeo V1.0 SOLID DMS1200 DAS LLD'],
                bdaEquipment: [],
                showBDA: false
            },
            'ADRF': {
                masterUnit: ['Syndeo V1.0 ADRF 202505 DAS'],
                bdaEquipment: ['Syndeo V1.0 ADRF 202505 SDR'],
                showBDA: true
            },
            'COMBA': {
                masterUnit: ['Syndeo V1.0 COMBA 202505 DAS LLD', 'Syndeo V1.0 COMBA 202505 Model 2014'],
                bdaEquipment: ['Syndeo V1.0 COMBA 202505 RX7W22 CLASSB', 'Syndeo V1.0 COMBA RX7W22 CLASS A LLD ERRCS'],
                showBDA: true
            }
        },
        'ERRCS': {
            'ADRF': {
                masterUnit: ['Syndeo V1.0 ADRF 202505 DAS'],
                bdaEquipment: ['Syndeo V1.0 ADRF 202505 SDR'],
                showBDA: true
            },
            'COMBA': {
                masterUnit: ['Syndeo V1.0 COMBA 202505 DAS LLD', 'Syndeo V1.0 COMBA 202505 Model 2014'],
                bdaEquipment: ['Syndeo V1.0 COMBA 202505 RX7W22 CLASSB', 'Syndeo V1.0 COMBA RX7W22 CLASS A LLD ERRCS'],
                showBDA: true
            },
        },
        'DAS & ERRCS': {
            'ADRF': {
                masterUnit: ['Syndeo V1.0 ADRF 202505 DAS'],
                bdaEquipment: ['Syndeo V1.0 ADRF 202505 SDR'],
                showBDA: true
            },
            'COMBA': {
                masterUnit: ['Syndeo V1.0 COMBA 202505 DAS LLD', 'Syndeo V1.0 COMBA 202505 Model 2014'],
                bdaEquipment: ['Syndeo V1.0 COMBA 202505 RX7W22 CLASSB', 'Syndeo V1.0 COMBA RX7W22 CLASS A LLD ERRCS'],
                showBDA: true
            }
        }
    };

    function clearDropdown(selectElement) {
        selectElement.innerHTML = '<option value="">Select the equipment</option>';
    }

    function populateDropdown(selectElement, options) {
        clearDropdown(selectElement);
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.textContent = option;
            selectElement.appendChild(optionElement);
        });
    }

    updateEquipmentFields = function() {
        const systemType = document.getElementById('system_type').value;
        const oem = document.getElementById('oem').value;
        const dasEqContainer = document.getElementById('das_equipment_container');
        const errcsEqContainer = document.getElementById('errcs_equipment_container');
        const dasEq = document.getElementById('das_equipment');
        const errcsEq = document.getElementById('errcs_equipment');
        const bdaContainer = document.getElementById('bda_quantity_container');

        // Clear both dropdowns first
        clearDropdown(dasEq);
        clearDropdown(errcsEq);

        // Hide both containers initially
        dasEqContainer.style.display = 'none';
        errcsEqContainer.style.display = 'none';
        dasEq.required = false;
        errcsEq.required = false;

        // Special handling for DAS system type
        if (systemType === 'DAS') {
            // For DAS, always show Master Unit Equipment (including when OEM is SOLID)
            // Move Master Unit Equipment to BDA Quantity's position
            const bdaParentGrid = bdaContainer.parentElement;
            if (bdaParentGrid && !bdaParentGrid.contains(dasEqContainer)) {
                bdaParentGrid.appendChild(dasEqContainer);
            }
            dasEqContainer.style.display = '';
            dasEq.required = true;
            
            // If OEM is selected, populate the equipment dropdown
            if (oem && equipmentConfig[systemType] && equipmentConfig[systemType][oem]) {
                const config = equipmentConfig[systemType][oem];
                if (config.masterUnit.length > 0) {
                    populateDropdown(dasEq, config.masterUnit);
                }
            }
            
            // Hide BDA Equipment completely for DAS
            errcsEqContainer.style.display = 'none';
            errcsEqContainer.style.visibility = 'hidden';
        } else if (systemType === 'ERRCS') {
            // For ERRCS: Show BDA Unit Equipment side by side with BDA Unit Quantity
            // Move BDA Unit Equipment to the same row as BDA Unit Quantity
            const bdaParentGrid = bdaContainer.parentElement;
            if (bdaParentGrid && !bdaParentGrid.contains(errcsEqContainer)) {
                bdaParentGrid.appendChild(errcsEqContainer);
            }
            errcsEqContainer.style.display = 'block';
            errcsEqContainer.style.visibility = 'visible';
            errcsEq.required = true;
            
            // Hide Master Unit Equipment
            dasEqContainer.style.display = 'none';
            dasEq.required = false;
            
            // If OEM is selected, populate the BDA equipment dropdown
            if (oem && equipmentConfig[systemType] && equipmentConfig[systemType][oem]) {
                const config = equipmentConfig[systemType][oem];
                if (config.showBDA && config.bdaEquipment.length > 0) {
                    populateDropdown(errcsEq, config.bdaEquipment);
                }
            }
        } else if (systemType === 'DAS & ERRCS') {
            // For DAS & ERRCS: Show Master Unit Equipment and BDA Unit Equipment side by side
            // Move both equipment containers to the same row
            const bdaParentGrid = bdaContainer.parentElement;
            if (bdaParentGrid && !bdaParentGrid.contains(dasEqContainer)) {
                bdaParentGrid.appendChild(dasEqContainer);
            }
            if (bdaParentGrid && !bdaParentGrid.contains(errcsEqContainer)) {
                bdaParentGrid.appendChild(errcsEqContainer);
            }
            
            // Show both equipment containers
            dasEqContainer.style.display = 'block';
            errcsEqContainer.style.display = 'block';
            errcsEqContainer.style.visibility = 'visible';
            dasEq.required = true;
            errcsEq.required = true;
            
            // If OEM is selected, populate both dropdowns
            if (oem && equipmentConfig[systemType] && equipmentConfig[systemType][oem]) {
                const config = equipmentConfig[systemType][oem];
                
                // Handle Master Unit Equipment
                if (config.masterUnit.length > 0) {
                    populateDropdown(dasEq, config.masterUnit);
                }
                
                // Handle BDA Equipment
                if (config.showBDA && config.bdaEquipment.length > 0) {
                    populateDropdown(errcsEq, config.bdaEquipment);
                }
            }
        } else {
            // Reset BDA Equipment visibility for other types
            errcsEqContainer.style.visibility = 'visible';
            
            // If both system type and OEM are selected, apply the configuration
            if (systemType && oem && equipmentConfig[systemType] && equipmentConfig[systemType][oem]) {
                const config = equipmentConfig[systemType][oem];
                
                // Handle Master Unit Equipment - but hide for ERRCS
                if (config.masterUnit.length > 0 && systemType !== 'ERRCS') {
                    dasEqContainer.style.display = '';
                    dasEq.required = true;
                    populateDropdown(dasEq, config.masterUnit);
                } else if (systemType === 'ERRCS') {
                    // Ensure Master Unit Equipment is hidden for ERRCS
                    dasEqContainer.style.display = 'none';
                    dasEq.required = false;
                }
                
                // Handle BDA Equipment
                if (config.showBDA && config.bdaEquipment.length > 0) {
                    errcsEqContainer.style.display = 'block';
                    errcsEqContainer.style.visibility = 'visible';
                    errcsEq.required = true;
                    populateDropdown(errcsEq, config.bdaEquipment);
                }
            } else if (systemType && !oem) {
                // Show containers based on system type but keep dropdowns empty until OEM is selected
                if (systemType === 'ERRCS') {
                    // For ERRCS, only show BDA Equipment, hide Master Unit Equipment
                    dasEqContainer.style.display = 'none';
                    errcsEqContainer.style.display = 'block';
                    errcsEqContainer.style.visibility = 'visible';
                    errcsEq.required = true;
                } else if (systemType === 'DAS & ERRCS') {
                    dasEqContainer.style.display = 'block';
                    errcsEqContainer.style.display = 'block';
                    errcsEqContainer.style.visibility = 'visible';
                    dasEq.required = true;
                    errcsEq.required = true;
                }
            }
        }
    }

    // Initial call
    updateEquipmentFields();

    // Add event listeners for both system type and OEM changes
    document.getElementById('system_type').addEventListener('change', updateEquipmentFields);
    document.getElementById('oem').addEventListener('change', updateEquipmentFields);
});
</script>

<script>
// Prevent form resubmission on page refresh
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('networkProvisioningForm');
    if (form) {
        // Store form submission state
        let isSubmitted = false;
        
        form.addEventListener('submit', function() {
            if (isSubmitted) {
                event.preventDefault();
                return false;
            }
            isSubmitted = true;
            
            // Disable submit button to prevent double submission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }
        });
        
        // Prevent form resubmission on page refresh
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            // Page was navigated back/forward, clear form
            form.reset();
        }
    }
});
</script>


@endsection