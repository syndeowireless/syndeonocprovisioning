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
<div class="min-h-[calc(100vh-80px)] bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-black">Network Provisioning Management</h1>
                <a href="{{ route('network-provisioning.create') }}" 
                   class="px-6 py-3 font-medium rounded-lg transition-all"
                   style="background-color: #13395d;color: white;border: 2px solid #fbbf0f;">
                    Create New Provisioning
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="text-3xl font-bold text-blue-700">{{ $networkProvisionings->count() }}</div>
                    <div class="text-sm text-blue-600">Total Provisionings</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="text-3xl font-bold text-green-700">{{ $allocatedIps->count() }}</div>
                    <div class="text-sm text-green-600">IPs in Use</div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="text-3xl font-bold text-yellow-700">{{ $networkProvisionings->sum('remote_unit_quantity') }}</div>
                    <div class="text-sm text-yellow-600">Total Remote Units</div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <div class="text-3xl font-bold text-purple-700">{{ $networkProvisionings->sum('master_unit_quantity') }}</div>
                    <div class="text-sm text-purple-600">Total Master Units</div>
                </div>
            </div>

            <!-- Network Provisionings Table -->
            @if($networkProvisionings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Range</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($networkProvisionings as $provisioning)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $provisioning->property_name }}</div>
                                <div class="text-sm text-gray-500">{{ $provisioning->oem ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($provisioning->property_type == 'Hotel') bg-blue-100 text-blue-800
                                    @elseif($provisioning->property_type == 'Factory') bg-gray-100 text-gray-800
                                    @elseif($provisioning->property_type == 'Office') bg-green-100 text-green-800
                                    @elseif($provisioning->property_type == 'Residencial') bg-purple-100 text-purple-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $provisioning->property_type ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $provisioning->system_type ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $provisioning->average_density ?? 'N/A' }} density</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($provisioning->allocatedIp)
                                <div class="text-sm font-mono text-gray-900">{{ $provisioning->allocatedIp->first_usable_ip }}</div>
                                <div class="text-sm font-mono text-gray-500">{{ $provisioning->allocatedIp->last_usable_ip }}</div>
                                @else
                                <span class="text-sm text-red-500">No IP allocated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    R: {{ $provisioning->remote_unit_quantity ?? 0 }} | 
                                    M: {{ $provisioning->master_unit_quantity ?? 0 }} | 
                                    B: {{ $provisioning->bda_quantity ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $provisioning->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($provisioning->allocatedIp)
                                <a href="{{ route('network-provisioning.pfsense', ['network_id' => $provisioning->id, 'ip_id' => $provisioning->allocatedIp->id]) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <form method="POST" action="{{ route('network-provisioning.release-ip', $provisioning->allocatedIp->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to release this IP range?')">
                                        Release IP
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400">No actions</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg mb-4">No network provisionings found</div>
                <a href="{{ route('network-provisioning.create') }}" 
                   class="px-6 py-3 font-medium rounded-lg transition-all inline-block"
                   style="background-color: #13395d;color: white;border: 2px solid #fbbf0f;">
                    Create Your First Provisioning
                </a>
            </div>
            @endif

            <!-- Available IP Ranges -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6 text-black">IP Range Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($allocatedIps as $ip)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-red-800">IN USE</span>
                            <span class="text-xs text-red-600">{{ $ip->network_range ?? 'N/A' }}</span>
                        </div>
                        <div class="text-sm font-mono text-red-900">
                            {{ $ip->first_usable_ip }} - {{ $ip->last_usable_ip }}
                        </div>
                        <div class="text-xs text-red-600 mt-1">{{ $ip->description ?? 'No description' }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

