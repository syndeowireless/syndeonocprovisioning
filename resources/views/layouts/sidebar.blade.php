<div class="sidebar bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
    <!-- Logo -->
    <div class="text-white flex items-center justify-center px-4">
        <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold flex items-center">
            <img src="https://via.placeholder.com/32x32" alt="Logo" class="h-8 w-8 mr-2">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>

    <!-- Navigation -->
    <nav>
        <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-home mr-2"></i> Home
        </a>

        <!-- Sales Menu -->
        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white focus:outline-none" onclick="toggleDropdown(this)">
                <span class="flex items-center"><i class="fas fa-dollar-sign mr-2"></i> Sales</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu hidden pl-4">
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Lead Generator</a>
                <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white focus:outline-none" onclick="toggleDropdown(this)">
                    <span class="flex items-center">ROM Generator</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="dropdown-menu hidden pl-4">
                    <a href="{{ route('formularios.create') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('formularios.create') ? 'bg-gray-700' : '' }}">Create New</a>
                    
                    <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Update ROM</a>
                    <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Update Pricing Model</a>
                </div>
            </div>
        </div>

        <!-- Operations Menu -->
        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white focus:outline-none" onclick="toggleDropdown(this)">
                <span class="flex items-center"><i class="fas fa-cogs mr-2"></i> Operations</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu hidden pl-4">
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Network Mgmt.</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">New Provisioning</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Search Provisioning</a>
            </div>
        </div>

        <!-- Others Menu -->
        <div class="relative">
            <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white focus:outline-none" onclick="toggleDropdown(this)">
                <span class="flex items-center"><i class="fas fa-users mr-2"></i> Others</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div class="dropdown-menu hidden pl-4">
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Update ROM (STAFF)</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Create New User</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Lock page</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">System Users</a>
            </div>
        </div>

        <!-- Admin Links (only for admin users) -->
        @if (Auth::user()->isAdmin())
            <div class="relative">
                <button class="w-full flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white focus:outline-none" onclick="toggleDropdown(this)">
                    <span class="flex items-center"><i class="fas fa-user-shield mr-2"></i> Admin</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="dropdown-menu hidden pl-4">
                    <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Gerenciar Usuários</a>
                    <a href="{{ route('formularios.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Gerenciar Formulários</a>
                </div>
            </div>
        @endif
    </nav>
</div>

<script>
    function toggleDropdown(button) {
        const dropdownMenu = button.nextElementSibling;
        dropdownMenu.classList.toggle('hidden');
        const icon = button.querySelector('i.fa-chevron-down');
        icon.classList.toggle('rotate-180');
    }
</script>


