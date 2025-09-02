<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <div class="mt-1">
                <div id="drop-zone" class="relative border-2 border-gray-300 border-dashed rounded-lg p-6 hover:border-gray-400 focus:border-indigo-500 transition-colors">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <label for="profile_picture" class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                    Drop image here or 
                                    <span class="text-indigo-600 hover:text-indigo-500">browse files</span>
                                </span>
                                <input 
                                    id="profile_picture" 
                                    name="profile_picture" 
                                    type="file" 
                                    class="sr-only" 
                                    accept="image/*"
                                    onchange="handleFileSelect(this)"
                                />
                            </label>
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, WebP up to 10MB</p>
                        </div>
                    </div>
                    <div id="file-preview" class="hidden mt-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                            <div class="flex items-center">
                                <img id="preview-image" class="h-10 w-10 rounded-full object-cover" src="" alt="Preview">
                                <div class="ml-3">
                                    <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                                    <p id="file-size" class="text-sm text-gray-500"></p>
                                </div>
                            </div>
                            <button type="button" onclick="clearFileSelection()" class="text-red-600 hover:text-red-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        // Handle drag and drop functionality
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('profile_picture');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    fileInput.files = files;
                    showPreview(file);
                }
            }
        }
        
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                showPreview(input.files[0]);
            }
        }
        
        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-size').textContent = formatFileSize(file.size);
                document.getElementById('file-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
        
        function clearFileSelection() {
            fileInput.value = '';
            document.getElementById('file-preview').classList.add('hidden');
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</section>