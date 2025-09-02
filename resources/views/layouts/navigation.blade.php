<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box" style="background-color: #13395d;
            padding-top: 20px;border-right:5px solid #fbbf0f" >
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
            
            
        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                    $authUser = Auth::user();
                    $defaultAvatar = asset('assets/images/users/user-4.jpg');
                    $avatarUrl = $defaultAvatar;
                    if ($authUser && $authUser->profile_picture) {
                        try {
                            // If already absolute (Cloudinary), use as-is
                            if (str_starts_with($authUser->profile_picture, 'http://') || str_starts_with($authUser->profile_picture, 'https://')) {
                                $avatarUrl = $authUser->profile_picture;
                            } elseif (config('filesystems.disks.azure.name') && config('filesystems.disks.azure.key')) {
                                $avatarUrl = \Illuminate\Support\Facades\Storage::disk('azure')->url($authUser->profile_picture);
                            } else {
                                $avatarUrl = asset('storage/' . $authUser->profile_picture);
                            }
                        } catch (\Throwable $e) {
                            $avatarUrl = $defaultAvatar;
                        }
                    }
                @endphp
                <img class="rounded-circle header-profile-user" src="{{ $avatarUrl }}" alt="Header Avatar">
            </button>

            <div class="dropdown-menu dropdown-menu-end" style="background: white !important;">
                <!-- Item normal -->
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="mdi mdi-account-circle font-size-17 text-muted align-middle me-1"></i>
                    {{ __('Settings') }}
                </a>

                <!-- Formulário de Logout CORRIGIDO -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger" style="cursor: pointer; width: 100%; text-align: left;">
                        <i class="mdi mdi-power font-size-17 text-muted align-middle me-1 text-danger"></i>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
        </div>
    </div>
</header>


  