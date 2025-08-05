@if(session("success"))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session("success") }}
    </div>
@endif

@extends("layouts.app")

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section("content")
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
                            <option value="Hotel" {{ old('property_type') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="Factory" {{ old('property_type') == 'Factory' ? 'selected' : '' }}>Factory</option>
                            <option value="Office" {{ old('property_type') == 'Office' ? 'selected' : '' }}>Office</option>
                            <option value="Residencial" {{ old('property_type') == 'Residencial' ? 'selected' : '' }}>Residencial</option>
                            <option value="Others" {{ old('property_type') == 'Others' ? 'selected' : '' }}>Others</option>
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
                        <label class="block text-gray-700 font-medium mb-2 text-black form-check-label" for="static_ip_check" style="margin-left: 5%">Static IP</label>
                        
                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                            <input class="sr-only peer" type="checkbox" id="static_ip_check" name="static_ip_check" value="1" onchange="toggleStaticIpFields()" style="width: 95%;">
                        </div>
                    </div>
                </div>

                <div id="static-ip-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label class="block text-gray-700 font-medium mb-2 text-black" for="static_ip" style="margin-left: 5%">IP</label>
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 form-control" type="text" id="static_ip" name="static_ip" placeholder="Digite o IP" style="width: 50%;margin-left: 5%;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="block text-gray-700 font-medium mb-2 text-black" for="static_mask" style="margin-left: 5%">Mask</label>
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 form-control" type="text" id="static_mask" name="static_mask" placeholder="Digite a máscara" style="width: 50%;margin-left: 5%;">
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
function toggleStaticIpFields() {
    var checkbox = document.getElementById('static_ip_check');
    var fields = document.getElementById('static-ip-fields');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}
</script>


@endsection

style="margin-left: 5%"
style="width: 90%;margin-left: 5%;"



style="width: 95%;"