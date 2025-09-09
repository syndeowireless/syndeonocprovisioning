<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Reset Password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter your new password below.
                </p>
            </div>
            
            <form class="mt-8 space-y-6" action="{{ route('password.reset') }}" method="POST" id="resetForm">
                @csrf
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                               placeholder="Enter new password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600">
                                <svg id="password-eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="password-strength" class="mt-1 text-xs"></div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm New Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               required 
                               class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                               placeholder="Confirm new password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600">
                                <svg id="password_confirmation-eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="password-match" class="mt-1 text-xs"></div>
                </div>

                <div>
                    <button type="submit" 
                            id="submitBtn"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        Reset Password
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" 
                       class="font-medium text-indigo-600 hover:text-indigo-500">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            } else {
                field.type = 'password';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength++;
            else feedback.push('At least 8 characters');
            
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('Lowercase letter');
            
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('Uppercase letter');
            
            if (/[0-9]/.test(password)) strength++;
            else feedback.push('Number');
            
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('Special character');
            
            const strengthDiv = document.getElementById('password-strength');
            const colors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-blue-600', 'text-green-600'];
            const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            strengthDiv.className = 'mt-1 text-xs ' + colors[strength - 1];
            strengthDiv.textContent = `Strength: ${labels[strength - 1]}${feedback.length > 0 ? ' (Missing: ' + feedback.join(', ') + ')' : ''}`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('password-match');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmPassword.length === 0) {
                matchDiv.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }
            
            if (password === confirmPassword) {
                matchDiv.className = 'mt-1 text-xs text-green-600';
                matchDiv.textContent = '✓ Passwords match';
                submitBtn.disabled = false;
            } else {
                matchDiv.className = 'mt-1 text-xs text-red-600';
                matchDiv.textContent = '✗ Passwords do not match';
                submitBtn.disabled = true;
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        // Initial state
        document.getElementById('submitBtn').disabled = true;
    </script>
</x-guest-layout>