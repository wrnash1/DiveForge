<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DiveForge Installation - Administrator Setup</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .input-group {
            transition: all 0.3s ease;
        }
        .input-group:focus-within {
            transform: translateY(-2px);
        }
        .password-strength {
            transition: all 0.3s ease;
        }
        .strength-bar {
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        .admin-icon {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .wave {
            animation: wave 2s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
        }
        .typing-animation {
            overflow: hidden;
            border-right: 2px solid #3b82f6;
            white-space: nowrap;
            animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #3b82f6; }
        }
        .requirement-check {
            transition: all 0.2s ease;
        }
        .requirement-check.met {
            color: #10b981;
        }
        .requirement-check.unmet {
            color: #ef4444;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Navigation Progress -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-water text-blue-600 text-2xl wave"></i>
                    <h1 class="text-xl font-bold text-gray-800">DiveForge Installation</h1>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm text-green-600">Welcome</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm text-green-600">Database</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">3</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600">Admin</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-sm font-semibold">4</span>
                        </div>
                        <span class="text-sm text-gray-500">Shop</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-sm font-semibold">5</span>
                        </div>
                        <span class="text-sm text-gray-500">Finish</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-12 text-white text-center">
                <div class="mb-6">
                    <i class="fas fa-user-shield text-6xl mb-4 admin-icon"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4">Administrator Account Setup</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Create your administrator account to manage your dive shop. This account will have full access to all DiveForge features.
                </p>
                <div class="mt-8 flex items-center justify-center space-x-8 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-key text-blue-200"></i>
                        <span>Full Access</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-blue-200"></i>
                        <span>Secure Login</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-cog text-blue-200"></i>
                        <span>System Management</span>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <!-- Error Messages -->
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <div>
                                <h4 class="font-semibold text-red-800">Setup Error</h4>
                                <p class="text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Security Notice -->
                <div class="mb-8 p-6 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Important Security Information</h3>
                            <div class="text-yellow-700 space-y-2 text-sm">
                                <p>• This administrator account will have complete control over your DiveForge installation</p>
                                <p>• Use a strong, unique password that you don't use elsewhere</p>
                                <p>• Keep your login credentials secure and don't share them</p>
                                <p>• You can create additional admin users later from the dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Administrator Form -->
                <form method="POST" action="{{ route('installer.step3.process') }}" id="adminForm">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Personal Information Section -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-user text-blue-600 mr-3"></i>
                                Personal Information
                            </h2>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div class="input-group">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-1"></i>First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="first_name" id="first_name" 
                                           value="{{ old('first_name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Enter your first name" required>
                                    <p class="text-xs text-gray-500 mt-1">This will appear in your profile and system logs</p>
                                    @error('first_name')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="input-group">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-1"></i>Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="last_name" id="last_name" 
                                           value="{{ old('last_name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Enter your last name" required>
                                    <p class="text-xs text-gray-500 mt-1">Your full name will be used for certifications and reports</p>
                                    @error('last_name')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Account Information Section -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-envelope text-blue-600 mr-3"></i>
                                Account Information
                            </h2>

                            <!-- Email Address -->
                            <div class="input-group mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-1"></i>Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="admin@yourdiveshop.com" required>
                                <p class="text-xs text-gray-500 mt-1">This will be your login username and receive system notifications</p>
                                <div id="emailValidation" class="mt-2 hidden">
                                    <div class="flex items-center space-x-2 text-sm">
                                        <i id="emailIcon" class="fas"></i>
                                        <span id="emailMessage"></span>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Security Section -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-lock text-blue-600 mr-3"></i>
                                Password Security
                            </h2>

                            <!-- Password Requirements -->
                            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h3 class="font-semibold text-blue-800 mb-3">Password Requirements</h3>
                                <div class="grid sm:grid-cols-2 gap-2">
                                    @foreach($password_requirements as $requirement)
                                        <div class="flex items-center space-x-2 text-sm">
                                            <i class="fas fa-circle requirement-check unmet" data-requirement="{{ $loop->index }}"></i>
                                            <span class="text-blue-700">{{ $requirement }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div class="input-group">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-1"></i>Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password" 
                                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="Enter a strong password" required>
                                        <button type="button" onclick="togglePassword('password')" 
                                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye" id="password_icon"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Strength Meter -->
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                            <span>Password Strength</span>
                                            <span id="strengthText">Enter password</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="strengthBar" class="strength-bar h-2 rounded-full bg-gray-300" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    
                                    @error('password')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="input-group">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-1"></i>Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="Confirm your password" required>
                                        <button type="button" onclick="togglePassword('password_confirmation')" 
                                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Match Indicator -->
                                    <div id="passwordMatch" class="mt-2 hidden">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <i id="matchIcon" class="fas"></i>
                                            <span id="matchMessage"></span>
                                        </div>
                                    </div>
                                    
                                    @error('password_confirmation')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Admin Privileges Info -->
                        <div class="p-6 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-crown text-green-600 text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-green-800 mb-2">Administrator Privileges</h3>
                                    <p class="text-green-700 mb-4">This account will have access to all DiveForge features including:</p>
                                    <div class="grid sm:grid-cols-2 gap-3 text-sm text-green-700">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>User Management</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Equipment Management</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Course Administration</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Booking Management</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Financial Reports</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>System Settings</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Data Export/Import</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Certification Management</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Two-Factor Authentication Notice -->
                        <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-mobile-alt text-purple-600"></i>
                                <div class="text-sm">
                                    <h4 class="font-semibold text-purple-800">Enhanced Security</h4>
                                    <p class="text-purple-700">After installation, you can enable two-factor authentication for additional security in your account settings.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('installer.step2.show') }}" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Database
                        </a>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                You can modify these details later
                            </div>
                            <button type="submit" id="continueBtn"
                                    class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <span class="flex items-center">
                                    Continue to Shop Setup
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="mt-8 grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-lightbulb text-yellow-500 text-xl"></i>
                    <h3 class="font-semibold text-gray-800">Password Tips</h3>
                </div>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Use a combination of uppercase and lowercase letters</li>
                    <li>• Include numbers and special characters</li>
                    <li>• Avoid common words or personal information</li>
                    <li>• Consider using a password manager</li>
                    <li>• Make it at least 12 characters long</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-4">
                    <i class="fas fa-question-circle text-blue-500 text-xl"></i>
                    <h3 class="font-semibold text-gray-800">What's Next?</h3>
                </div>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li>• Configure your dive shop details</li>
                    <li>• Complete the installation process</li>
                    <li>• Access your DiveForge dashboard</li>
                    <li>• Start adding equipment and courses</li>
                    <li>• Invite team members to join</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeForm();
            initializePasswordStrength();
            initializeEmailValidation();
        });

        function initializeForm() {
            const form = document.getElementById('adminForm');
            const continueBtn = document.getElementById('continueBtn');
            
            // Form validation
            form.addEventListener('input', function() {
                validateForm();
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showNotification('Please fill in all required fields correctly.', 'error');
                    return;
                }
                
                // Show loading state
                continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Administrator...';
                continueBtn.disabled = true;
            });
        }

        function validateForm() {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            const continueBtn = document.getElementById('continueBtn');
            
            const isValid = firstName !== '' && 
                           lastName !== '' && 
                           isValidEmail(email) && 
                           isStrongPassword(password) && 
                           password === passwordConfirm;
            
            continueBtn.disabled = !isValid;
            
            if (isValid) {
                continueBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                continueBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            return isValid;
        }

        function initializePasswordStrength() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            
            passwordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value);
                updatePasswordMatch();
            });
            
            confirmInput.addEventListener('input', function() {
                updatePasswordMatch();
            });
        }

        function updatePasswordStrength(password) {
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const requirements = document.querySelectorAll('.requirement-check');
            
            if (!password) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'strength-bar h-2 rounded-full bg-gray-300';
                strengthText.textContent = 'Enter password';
                requirements.forEach(req => {
                    req.classList.remove('met');
                    req.classList.add('unmet');
                });
                return;
            }
            
            let score = 0;
            const checks = [
                password.length >= 12,                          // Minimum 12 characters
                /[a-z]/.test(password),                        // Lowercase letter
                /[A-Z]/.test(password),                        // Uppercase letter
                /\d/.test(password),                           // Number
                /[@$!%*?&]/.test(password),                    // Special character
                password.length >= 16                          // Extra points for length
            ];
            
            checks.forEach((check, index) => {
                if (check) score++;
                if (index < requirements.length) {
                    if (check) {
                        requirements[index].classList.remove('unmet');
                        requirements[index].classList.add('met');
                        requirements[index].classList.remove('fa-circle');
                        requirements[index].classList.add('fa-check-circle');
                    } else {
                        requirements[index].classList.remove('met');
                        requirements[index].classList.add('unmet');
                        requirements[index].classList.remove('fa-check-circle');
                        requirements[index].classList.add('fa-circle');
                    }
                }
            });
            
            const percentage = (score / 6) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (score <= 2) {
                strengthBar.className = 'strength-bar h-2 rounded-full bg-red-500';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-red-600';
            } else if (score <= 4) {
                strengthBar.className = 'strength-bar h-2 rounded-full bg-yellow-500';
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-yellow-600';
            } else if (score <= 5) {
                strengthBar.className = 'strength-bar h-2 rounded-full bg-blue-500';
                strengthText.textContent = 'Good';
                strengthText.className = 'text-blue-600';
            } else {
                strengthBar.className = 'strength-bar h-2 rounded-full bg-green-500';
                strengthText.textContent = 'Excellent';
                strengthText.className = 'text-green-600';
            }
        }

        function updatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('passwordMatch');
            const matchIcon = document.getElementById('matchIcon');
            const matchMessage = document.getElementById('matchMessage');
            
            if (!confirm) {
                matchDiv.classList.add('hidden');
                return;
            }
            
            matchDiv.classList.remove('hidden');
            
            if (password === confirm) {
                matchIcon.className = 'fas fa-check-circle text-green-500';
                matchMessage.textContent = 'Passwords match';
                matchMessage.className = 'text-green-600';
            } else {
                matchIcon.className = 'fas fa-times-circle text-red-500';
                matchMessage.textContent = 'Passwords do not match';
                matchMessage.className = 'text-red-600';
            }
        }

        function initializeEmailValidation() {
            const emailInput = document.getElementById('email');
            let emailTimeout;
            
            emailInput.addEventListener('input', function() {
                clearTimeout(emailTimeout);
                emailTimeout = setTimeout(()