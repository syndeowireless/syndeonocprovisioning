<!-- resources/views/layouts/sidebar.blade.php -->
<div class="flex h-screen bg-gray-100 dark:" style="background-color: #57585a;">
        <div id="layout-wrapper">

            <header id="page-topbar" style="background-color: #3e3e3f">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box" style="background-color: #3e3e3f;
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

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="fa fa-search"></span>
                </div>
            </form>

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
                    
                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



<div class="dropdown d-none d-md-block ms-2">
    <button type="button" class="btn header-item waves-effect d-flex align-items-center" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="me-2" src="{{ asset('assets/images/flags/us_flag.jpg') }}" alt="Header Language" height="10px" style="display: inline-block; vertical-align: middle;height: 20px;">
        <span class="align-middle">English</span>
        <span class="mdi mdi-chevron-down ms-1"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-end">

        <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item d-flex align-items-center">
            <img src="{{ asset('assets/images/flags/germany_flag.jpg') }}" alt="user-image" class="me-2" height="12"> 
            <span class="align-middle">German</span>
        </a>

        <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item d-flex align-items-center">
            <img src="{{ asset('assets/images/flags/italy_flag.jpg') }}" alt="user-image" class="me-2" height="12"> 
            <span class="align-middle">Italian</span>
        </a>

        <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item d-flex align-items-center">
            <img src="{{ asset('assets/images/flags/french_flag.jpg') }}" alt="user-image" class="me-2" height="12"> 
            <span class="align-middle">French</span>
        </a>

        <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item d-flex align-items-center">
            <img src="{{ asset('assets/images/flags/spain_flag.jpg') }}" alt="user-image" class="me-2" height="12"> 
            <span class="align-middle">Spanish</span>
        </a>

        <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item d-flex align-items-center">
            <img src="{{ asset('assets/images/flags/russia_flag.jpg') }}" alt="user-image" class="me-2" height="12"> 
            <span class="align-middle">Russian</span>
        </a>
    </div>
</div>

            <div class="dropdown d-none d-lg-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen font-size-24" style="color: white"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-bell" style="color: white;"></i>
                    <span class="badge text-bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0"> Notifications (258) </h5>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="javascript:void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                    <span class="avatar-title border-success rounded-circle ">
                                        <i class="mdi mdi-cart-outline"></i>
                                    </span>
                                </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Your order is placed</h6>
                                    <div class="text-muted">
                                        <p class="mb-1">If several languages coalesce the grammar</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                    <span class="avatar-title border-warning rounded-circle ">
                                        <i class="mdi mdi-message"></i>
                                    </span>
                                </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">New Message received</h6>
                                    <div class="text-muted">
                                        <p class="mb-1">You have 87 unread messages</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                    <span class="avatar-title border-info rounded-circle ">
                                        <i class="mdi mdi-glass-cocktail"></i>
                                    </span>
                                </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Your item is shipped</h6>
                                    <div class="text-muted">
                                        <p class="mb-1">It is a long established fact that a reader will</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                    <span class="avatar-title border-primary rounded-circle ">
                                        <i class="mdi mdi-cart-outline"></i>
                                    </span>
                                </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Your order is placed</h6>
                                    <div class="text-muted">
                                        <p class="mb-1">Dummy text of the printing and typesetting industry.</p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                    <span class="avatar-title border-warning rounded-circle ">
                                        <i class="mdi mdi-message"></i>
                                    </span>
                                </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">New Message received</h6>
                                    <div class="text-muted">
                                        <p class="mb-1">You have 87 unread messages</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top">
                        <a class="btn btn-sm btn-link font-size-14 w-100 text-center" href="javascript:void(0)">
                            View all
                        </a>
                    </div>
                </div>
            </div>
            

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/user-4.jpg') }}"
                        alt="Header Avatar">
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <x-dropdown-link :href="route('profile.edit')" class="dropdown-item" style=".hover: pink !important"><i class="mdi mdi-cog font-size-17 text-muted align-middle me-1"></i> {{ __('Settings') }}</x-dropdown-link>
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger px-4" :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"><i class="mdi mdi-power font-size-17 text-muted align-middle me-1 text-danger"></i>{{ __('Log Out') }}</a>
                </div>
            </div>
            
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="mdi mdi-spin mdi-cog" style="color:white;"></i>
                </button>
            </div>
        </div>
    </div>
</header>
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu"style="background-color: #3e3e3f;">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu" >
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Sales</li>

                            <li>
                                <a class="waves-effect" href="{{ route('sales.rom-generator.create') }}">
                                    <i class="ion ion-md-add-circle"></i>
                                    <!-- <span class="badge rounded-pill bg-primary float-end">2</span> -->
                                    <span>ROM Generator</span>
                                </a>
                                
                            </li>

                            <li>
                                <a class=" waves-effect" href="{{ route('sales.rom-generator.search') }}">
                                    <i class="ion ion-md-search"></i>
                                    <span>Search ROM</span>
                                </a>
                            </li>

                        <li class="menu-title">Operations</li>

                        <li class="menu-title">Other</li>
                           

                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>


</div>