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
    <div class="w-full max-w-4xl px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-2xl font-bold mb-8 text-center text-black">Create Network Provisioning</h1>

            <form method="POST" action="" class="space-y-6">
                @csrf

                <!-- Linha 1: Property Name / OEM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Property Name</label>
                        <input type="text" name="property_name" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the property name">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">OEM</label>
                        <input type="text" name="oem" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the OEM">
                    </div>
                </div>

                <!-- Linha 2: Property Address -->
                <div class="form-group">
                    <label class="block text-gray-700 font-medium mb-2 text-black">Property Address</label>
                    <input type="text" name="property_address" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Type the property full address">
                </div>

                <!-- Linha 3: Master Unit Quantity / BDA Quantity -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Master Unit Quantity</label>
                        <input type="number" name="master_unit_quantity" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the quantity">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">BDA Quantity</label>
                        <input type="number" name="bda_quantity" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the quantity">
                    </div>
                </div>

                <!-- Linha 4: Latitude / Longitude -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Latitude</label>
                        <input type="text" name="latitude" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the latitude">
                    </div>
                    <div class="form-group">
                        <label class="block text-gray-700 font-medium mb-2 text-black">Longitude</label>
                        <input type="text" name="longitude" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Type the longitude">
                    </div>
                </div>

                <!-- Linha 5: Remote Unit Quantity -->
                <div class="form-group">
                    <label class="block text-gray-700 font-medium mb-2 text-black">Remote Unit Quantity</label>
                    <input type="number" name="remote_unit_quantity" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Type the quantity">
                </div>

                <!-- Linha 6: Property Type / Average Density -->
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

                <!-- Linha 7: System Type -->
                <div class="form-group">
                    <label class="block text-gray-700 font-medium mb-2 text-black">System Type</label>
                    <select name="system_type" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select the system type</option>
                        <option value="DAS">DAS</option>
                        <option value="ERRCS">ERRCS</option>
                        <option value="DAS & ERRCS">DAS & ERRCS</option>
                    </select>
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
@endsection