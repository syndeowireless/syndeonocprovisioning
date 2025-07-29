<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box" style="background-color: #13395d;
            padding-top: 20px;" >
                <a href="" class="logo logo-dark" >
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="32">
                    </span>
                </a>

                <a href="" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22" style="padding-top: 20%;">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="32">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>


        </div>
            
        <div class="d-flex">
            <div class="dropdown d-none d-lg-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen font-size-24"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-bell"></i>
                    <span class="badge text-bg-danger rounded-pill">3</span>
                </button>
            </div>
            
            
<div class="dropdown d-inline-block">
    <button 
        type="button" 
        class="btn header-item waves-effect" 
        id="page-header-user-dropdown"
        data-bs-toggle="dropdown" 
        aria-expanded="false"    >
        <img class="rounded-circle header-profile-user" 
            src="assets/images/users/user-4.jpg"
            alt="Header Avatar"
        >
    </button>
    
    <div class="dropdown-menu dropdown-menu-end">
        <!-- Item normal -->
        <a class="dropdown-item" href="#">
            <i class="mdi mdi-account-circle font-size-17 text-muted align-middle me-1"></i>
            {{ __('Settings') }}
        </a>
        
        <!-- Formulário de Logout CORRIGIDO -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="dropdown-item text-danger"  <!-- Classes movidas para o botão -->
                style="cursor: pointer; width: 100%; text-align: left;"
            >
                <i class="mdi mdi-power font-size-17 text-muted align-middle me-1 text-danger"></i>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
        </div>
    </div>
</header>


  

