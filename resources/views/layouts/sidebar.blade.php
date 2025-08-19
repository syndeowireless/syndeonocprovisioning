<div class="vertical-menu" style="background: #13395d !important; border-right: 5px solid #fbbf0f;" collapsed>

                <div data-simplebar class="h-100">

                    <!-- Logo Section -->
                    <div class="navbar-brand-box sidebar-logo" style="background-color: #13395d !important; padding: 20px; text-align: center; border-right: 5px solid #fbbf0f;">
                        <a href="#" class="logo logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-light" height="50">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-sm" height="50">
                            </span>
                        </a>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu" style="background-color: #13395d !important;">
                            <li class="menu-title" style="color: white; background-color: #13395d !important;">Main</li>

                            <li style="background-color: #13395d !important;">
                                <a href="#" class="waves-effect" style="color: white !important; background-color: #13395d !important; padding: 12px 20px; display: block;">
                                    <i class="mdi mdi-home" style="color: white !important;"></i>
                                    
                                    <span>Home</span>
                                </a>
                            </li>
                            <!--<li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect" style="color: white;">
                                    <i class="mdi mdi-cash-multiple" style="color: white;"></i>
                                    <span>Sales</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="true">
                                    <li><a href="javascript: void(0);" style="color: white;">Reviews</a></li>
                                    <li><a href="javascript: void(0);" style="color: white;">Lead Generator</a></li>
                                    <li><a href="javascript: void(0);" class="has-arrow" style="color: white;">Rom Generator</a>
                                        <ul class="sub-menu" aria-expanded="true">
                                            <li><a href="javascript: void(0);" style="color: white;">Create New</a></li>
                                            <li><a href="javascript: void(0);" style="color: white;">Search ROM</a></li>
                                            <li><a href="javascript: void(0);" style="color: white;">Update ROM</a></li>
                                            <li><a href="javascript: void(0);" style="color: white;">Update Pricing Model</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>-->

                            <li style="background-color: #13395d !important;">
                                <a href="javascript: void(0);" class="has-arrow waves-effect" style="color: white !important; background-color: #13395d !important; padding: 12px 20px; display: block;">
                                    <i class="mdi mdi-atom" style="color: white !important;"></i>
                                    <span>Operations</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="true" style="background-color: #13395d !important;">
                                    <li style="background-color: #13395d !important;"><a href="javascript: void(0);" class="has-arrow" style="color: white !important; background-color: #13395d !important; padding: 8px 20px 8px 40px; display: block;">Network mgmt.</a>
                                        <ul class="sub-menu" aria-expanded="true" style="background-color: #13395d !important;">
                                            <li style="background-color: #13395d !important;"><a href="{{ route('network-provisioning.create') }}" style="color: white !important; background-color: #13395d !important; padding: 8px 20px 8px 60px; display: block;">New Provisioning</a></li>
                                            <li style="background-color: #13395d !important;"><a href="#" style="color: white !important; background-color: #13395d !important; padding: 8px 20px 8px 60px; display: block;">Search Provisioning</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <!--<li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect" style="color: white;">
                                    <i class="mdi mdi-collage" style="color: white;"></i>
                                    <span>Others</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="true">
                                    <li><a href="javascript: void(0);" style="color: white;">Update ROM (STAFF)</a></li>
                                    <li><a href="javascript: void(0);" style="color: white;"> Create New User</a></li>
                                    <li><a href="javascript: void(0);" style="color: white;"> Lock page</a></li>
                                    <li><a href="javascript: void(0);" style="color: white;"> System Users</a></li>
                                </ul>
                            </li>-->


                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <style>
                /* Sidebar Logo styling */
                .sidebar-logo {
                    background-color: #13395d !important;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                    border-right: 5px solid #fbbf0f !important;
                }
                
                .sidebar-logo .logo {
                    display: block;
                    text-decoration: none;
                }
                
                .sidebar-logo .logo img {
                    transition: all 0.3s ease;
                }
                
                /* Show/hide logos based on sidebar state */
                .vertical-menu:not([collapsed]) .logo-sm {
                    display: none;
                }
                
                .vertical-menu[collapsed] .logo-lg {
                    display: none;
                }
                
                .vertical-menu[collapsed] .logo-sm {
                    display: inline-block;
                }
                
                .vertical-menu:not([collapsed]) .logo-lg {
                    display: inline-block;
                }
                
                /* Collapsed sidebar logo adjustments */
                .vertical-menu[collapsed] .sidebar-logo {
                    padding: 15px 10px !important;
                }
                
                /* Sobrescreve todos os estilos do metismenu */
                .vertical-menu,
                .vertical-menu .metismenu,
                .vertical-menu .metismenu li,
                .vertical-menu .metismenu li a,
                .vertical-menu .metismenu .sub-menu,
                .vertical-menu .metismenu .sub-menu li,
                .vertical-menu .metismenu .sub-menu li a {
                    background-color: #13395d !important;
                }
                
                /* Hover effect com a cor amarela */
                .vertical-menu .metismenu a:hover,
                .vertical-menu .metismenu li:hover > a,
                .vertical-menu .metismenu .sub-menu a:hover {
                    background-color: #fbbf0f !important;
                    color: #13395d !important;
                }
                
                /* Estado ativo */
                .vertical-menu .metismenu li.mm-active > a,
                .vertical-menu .metismenu .sub-menu li.mm-active > a {
                    background-color: #fbbf0f !important;
                    color: #13395d !important;
                }
                
                /* Força a cor do texto para branco em todos os estados */
                .vertical-menu .metismenu a,
                .vertical-menu .metismenu .sub-menu a,
                .vertical-menu .menu-title {
                    color: white !important;
                }
                
                /* Ícones sempre brancos, exceto no hover */
                .vertical-menu .metismenu i {
                    color: white !important;
                }
                
                .vertical-menu .metismenu a:hover i,
                .vertical-menu .metismenu li.mm-active > a i {
                    color: #13395d !important;
                }
                
                /* Remove qualquer borda ou outline */
                .vertical-menu .metismenu a {
                    border: none !important;
                    outline: none !important;
                }
                
                /* Garante que sub-menus também tenham o fundo correto */
                .vertical-menu .metismenu .collapse.show,
                .vertical-menu .metismenu .collapsing {
                    background-color: #13395d !important;
                }
                
                /* Collapsed sidebar adjustments */
                .vertical-menu[collapsed] {
                    width: 70px;
                }
                
                .vertical-menu[collapsed] .navbar-brand-box {
                    padding: 15px 10px;
                }
                
                .vertical-menu[collapsed] .menu-title,
                .vertical-menu[collapsed] span {
                    display: none;
                }
                
            </style>