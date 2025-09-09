<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verify OTP
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    We've sent a 6-digit OTP to your email address. Enter it below to continue.
                </p>
                <p class="mt-1 text-center text-xs text-gray-500">
                    OTP expires in 2 minutes
                </p>
            </div>
            
            <form class="mt-8 space-y-6" action="{{ route('password.verify-otp') }}" method="POST">
                @csrf
                
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700">
                        Enter OTP
                    </label>
                    <div class="mt-1">
                        <input id="otp" 
                               name="otp" 
                               type="text" 
                               maxlength="6"
                               required 
                               value="{{ old('otp') }}"
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm text-center text-2xl tracking-widest @error('otp') border-red-500 @enderror"
                               placeholder="000000"
                               autocomplete="off">
                    </div>
                    @error('otp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Verify OTP
                    </button>
                    
                    <button type="button" 
                            onclick="resendOtp()"
                            class="flex-1 group relative flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Resend OTP
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('password.request') }}" 
                       class="font-medium text-indigo-600 hover:text-indigo-500">
                        Back to Email
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-focus on OTP input
        document.getElementById('otp').focus();
        
        // Only allow numbers in OTP input
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Resend OTP function
        function resendOtp() {
            if (confirm('Are you sure you want to resend the OTP?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("password.resend-otp") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-guest-layout>
