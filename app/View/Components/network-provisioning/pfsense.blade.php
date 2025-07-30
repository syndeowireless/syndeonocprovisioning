@if(session("success"))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session("success") }}
    </div>
@endif

@extends("layouts.app")

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section("content")
<div class="flex justify-center items-center min-h-[calc(100vh-80px)] bg-gray-50">
    <div class="max-w-6xl px-4 py-8" style="width: 90%;padding-top: 3%;">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8 text-center text-black">pfSense Network Configuration</h1>
            
            <!-- Network Information Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-blue-800">Network Provisioning Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <span class="font-medium text-gray-700">Property Name:</span>
                        <span class="text-gray-900">{{ $networkManagement->property_name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">OEM:</span>
                        <span class="text-gray-900">{{ $networkManagement->oem ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Property Type:</span>
                        <span class="text-gray-900">{{ $networkManagement->property_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">System Type:</span>
                        <span class="text-gray-900">{{ $networkManagement->system_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Average Density:</span>
                        <span class="text-gray-900">{{ $networkManagement->average_density ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Created:</span>
                        <span class="text-gray-900">{{ $networkManagement->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- IP Allocation Information -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Allocated IP Range</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-4 border border-green-300">
                        <h3 class="font-semibold text-green-700 mb-2">First Usable IP</h3>
                        <div class="text-2xl font-mono text-green-900 bg-green-100 p-3 rounded border">
                            {{ $allocatedIp->first_usable_ip }}
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-green-300">
                        <h3 class="font-semibold text-green-700 mb-2">Last Usable IP</h3>
                        <div class="text-2xl font-mono text-green-900 bg-green-100 p-3 rounded border">
                            {{ $allocatedIp->last_usable_ip }}
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-600">
                        <strong>Network Range:</strong> {{ $allocatedIp->network_range ?? 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <strong>Description:</strong> {{ $allocatedIp->description ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- pfSense Configuration Commands -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">pfSense Configuration Commands</h2>
                <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                    <div class="mb-2"># Configure interface with allocated IP range</div>
                    <div class="mb-2">ifconfig em0 {{ $allocatedIp->first_usable_ip }}/24</div>
                    <div class="mb-2"># Set up DHCP pool</div>
                    <div class="mb-2">dhcp-range={{ $allocatedIp->first_usable_ip }},{{ $allocatedIp->last_usable_ip }},24h</div>
                    <div class="mb-2"># Configure firewall rules</div>
                    <div>pass in on em0 from {{ $allocatedIp->network_range ?? $allocatedIp->first_usable_ip.'/24' }} to any</div>
                </div>
            </div>

            <!-- Equipment Summary -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-yellow-800">Equipment Summary</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center bg-white rounded-lg p-4 border border-yellow-300">
                        <div class="text-3xl font-bold text-yellow-700">{{ $networkManagement->remote_unit_quantity ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Remote Units</div>
                    </div>
                    <div class="text-center bg-white rounded-lg p-4 border border-yellow-300">
                        <div class="text-3xl font-bold text-yellow-700">{{ $networkManagement->master_unit_quantity ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Master Units</div>
                    </div>
                    <div class="text-center bg-white rounded-lg p-4 border border-yellow-300">
                        <div class="text-3xl font-bold text-yellow-700">{{ $networkManagement->bda_quantity ?? 0 }}</div>
                        <div class="text-sm text-gray-600">BDA Units</div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            @if($networkManagement->latitude && $networkManagement->longitude)
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-purple-800">Location Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-700">Address:</span>
                        <span class="text-gray-900">{{ $networkManagement->property_address ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Coordinates:</span>
                        <span class="text-gray-900">{{ $networkManagement->latitude }}, {{ $networkManagement->longitude }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('network-provisioning.create') }}" 
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Create New Provisioning
                </a>
                <a href="{{ route('network-provisioning.index') }}" 
                   class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    View All Provisionings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

