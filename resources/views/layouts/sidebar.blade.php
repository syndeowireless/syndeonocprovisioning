            <div class="flex h-screen bg-gray-100 dark:" style="background-color: #57585a;">
        <div id="layout-wrapper">

            <header id="page-topbar" style="background-color: #3e3e3f">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box" style="background-color: #3e3e3f;
    padding-top: 20px;" >
                <a href="{{ route('logo.redirect') }}" class="logo logo-dark" >
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="32">
                    </span>
                </a>

                <a href="{{ route('logo.redirect') }}" class="logo logo-light">
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
            
            <div class="vertical-menu"style="background-color: #3e3e3f;">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu" >
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Sales</li>

                            <li>
                                <a class="waves-effect" href="">
                                    <i class="ion ion-md-add-circle"></i>
                                    <!-- <span class="badge rounded-pill bg-primary float-end">2</span> -->
                                    <span>ROM Generator</span>
                                </a>
                                
                            </li>

                            <li>
                                <a class=" waves-effect" href="">
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