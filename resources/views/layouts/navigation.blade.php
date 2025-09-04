<header id="page-topbar">
    <!-- Yellow Line -->
    <div style="height: 4px; background: linear-gradient(90deg, #fbbf0f 0%, #f1c40f 50%, #fbbf0f 100%); width: 100%; box-shadow: 0 2px 4px rgba(251, 191, 15, 0.3);"></div>
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
            <button type="button" class="btn header-item waves-effect d-flex align-items-center gap-2" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; padding: 8px 16px; border-radius: 12px; transition: all 0.3s ease;">
                @php
                    $authUser = Auth::user();
                    $defaultAvatar = asset('assets/images/users/user-4.jpg');
                    $avatarUrl = $defaultAvatar;
                    $showImage = true;
                    
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
                            $showImage = false;
                        }
                    } else {
                        $showImage = false;
                    }
                @endphp
                
                @if($showImage)
                    <img class="rounded-circle" src="{{ $avatarUrl }}" alt="User Avatar" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #fbbf0f;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                    <i class="mdi mdi-account-circle font-size-24 text-muted" style="display: none; color: #6c757d !important; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 50%; border: 2px solid #fbbf0f;"></i>
                @else
                    <i class="mdi mdi-account-circle font-size-24 text-muted" style="color: #6c757d !important; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 50%; border: 2px solid #fbbf0f;"></i>
                @endif
                
                <div class="d-flex flex-column align-items-start" style="margin-left: 12px;">
                    <span class="fw-semibold text-dark" style="font-size: 14px; line-height: 1.2; margin-bottom: 2px;">{{ $authUser->name ?? 'User' }}</span>
                    <span class="text-muted" style="font-size: 12px; line-height: 1.2;">{{ $authUser->email ?? 'user@example.com' }}</span>
                </div>
                
                <i class="mdi mdi-chevron-down text-muted" style="margin-left: 8px; font-size: 16px; transition: transform 0.3s ease;"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="background: white !important; min-width: 280px; padding: 0; border-radius: 16px; margin-top: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;">
                <!-- User Info Header -->
                <div class="dropdown-header p-3" style="background: #13395d; color: white; border-radius: 16px 16px 0 0; margin: 0;">
                    <div class="d-flex align-items-center">
                        @if($showImage)
                            <img class="rounded-circle me-3" src="{{ $avatarUrl }}" alt="User Avatar" style="width: 48px; height: 48px; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <i class="mdi mdi-account-circle font-size-32 text-white me-3" style="display: none; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 50%; border: 3px solid rgba(255,255,255,0.3);"></i>
                        @else
                            <i class="mdi mdi-account-circle font-size-32 text-white me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); border-radius: 50%; border: 3px solid rgba(255,255,255,0.3);"></i>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold" style="font-size: 16px; margin: 0;">{{ $authUser->name ?? 'User' }}</h6>
                            <p class="mb-0 opacity-75" style="font-size: 13px; margin: 0;">{{ $authUser->email ?? 'user@example.com' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Divider -->
                <hr class="my-0" style="border-color: #e9ecef; margin: 0;">
                
                <!-- Menu Items -->
                <div class="p-2">
                    <a class="dropdown-item d-flex align-items-center py-2 px-3" href="{{ route('profile.edit') }}" style="border-radius: 8px; transition: all 0.2s ease; color: #495057;">
                        <i class="mdi mdi-account-edit font-size-18 text-primary me-3" style="width: 20px; text-align: center;"></i>
                        <span class="fw-medium">{{ __('Profile') }}</span>
                    </a>

                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" class="d-block">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center py-2 px-3 w-100 text-start border-0" style="background: transparent; border-radius: 8px; transition: all 0.2s ease; color: #dc3545; cursor: pointer;">
                            <i class="mdi mdi-power font-size-18 text-danger me-3" style="width: 20px; text-align: center;"></i>
                            <span class="fw-medium">{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</header>


  