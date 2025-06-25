<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DiveForge Installation - Welcome</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .requirement-item {
            transition: all 0.3s ease;
        }
        .requirement-item:hover {
            transform: translateX(4px);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .wave {
            animation: wave 2s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
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
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">1</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600">Welcome</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-sm font-semibold">2</span>
                        </div>
                        <span class="text-sm text-gray-500">Database</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-sm font-semibold">3</span>
                        </div>
                        <span class="text-sm text-gray-500">Admin</span>
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
                    <i class="fas fa-water text-6xl mb-4 wave"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4">Welcome to DiveForge</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    The complete dive shop management solution. Let's get your diving business set up and ready to make waves!
                </p>
                <div class="mt-8 flex items-center justify-center space-x-8 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-swimming-pool text-blue-200"></i>
                        <span>Equipment Management</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-blue-200"></i>
                        <span>Course Scheduling</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-certificate text-blue-200"></i>
                        <span>Certification Tracking</span>
                    </div>
                </div>
            </div>

            <!-- System Requirements -->
            <div class="p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-check text-blue-600 mr-3"></i>
                        System Requirements Check
                    </h2>
                    <p class="text-gray-600">
                        Before we dive in, let's make sure your server meets all the requirements for DiveForge.
                    </p>
                </div>

                <!-- Requirements Grid -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- PHP Version -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fab fa-php text-purple-600 mr-2"></i>
                            PHP Configuration
                        </h3>
                        <div class="requirement-item flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-{{ $system_check['php_version']['passed'] ? 'check-circle text-green-500' : 'times-circle text-red-500' }}"></i>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $system_check['php_version']['name'] }}</div>
                                    <div class="text-sm text-gray-500">Current: {{ $system_check['php_version']['current'] }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($system_check['php_version']['passed'])
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">✓ Passed</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">✗ Failed</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Disk Space -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-hdd text-gray-600 mr-2"></i>
                            Storage Requirements
                        </h3>
                        <div class="requirement-item flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-{{ $system_check['disk_space']['passed'] ? 'check-circle text-green-500' : 'times-circle text-red-500' }}"></i>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $system_check['disk_space']['name'] }}</div>
                                    <div class="text-sm text-gray-500">Available: {{ $system_check['disk_space']['available_mb'] }}MB</div>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($system_check['disk_space']['passed'])
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">✓ Passed</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">✗ Failed</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PHP Extensions -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-puzzle-piece text-blue-600 mr-2"></i>
                        Required PHP Extensions
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($system_check['extensions'] as $extension => $details)
                            <div class="requirement-item flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-{{ $details['passed'] ? 'check-circle text-green-500' : 'times-circle text-red-500' }} text-sm"></i>
                                    <span class="text-sm font-medium text-gray-700">{{ $details['name'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Directory Permissions -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-folder-lock text-yellow-600 mr-2"></i>
                        Directory Permissions
                    </h3>
                    <div class="space-y-3">
                        @foreach($system_check['permissions'] as $directory => $details)
                            <div class="requirement-item flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-{{ $details['passed'] ? 'check-circle text-green-500' : 'times-circle text-red-500' }}"></i>
                                    <div>
                                        <div class="font-medium text-gray-800">{{ $details['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $details['path'] }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($details['passed'])
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Writable</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                            @if(!$details['exists'])
                                                Missing
                                            @else
                                                Not Writable
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Overall Status -->
                <div class="mb-8 p-6 rounded-xl {{ $system_check['all_passed'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($system_check['all_passed'])
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xl"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            @if($system_check['all_passed'])
                                <h3 class="text-lg font-semibold text-green-800">Great! Your server is ready for DiveForge</h3>
                                <p class="text-green-700">All system requirements have been met. You can proceed with the installation.</p>
                            @else
                                <h3 class="text-lg font-semibold text-red-800">System Requirements Not Met</h3>
                                <p class="text-red-700">Please resolve the failed requirements above before continuing with the installation.</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0">
                            <button onclick="checkRequirements()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Recheck
                            </button>
                        </div>
                    </div>
                </div>

                <!-- License Agreement -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-contract text-purple-600 mr-2"></i>
                        License Agreement
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-6 max-h-64 overflow-y-auto border">
                        <div class="prose prose-sm max-w-none">
                            <h4 class="font-semibold text-gray-800 mb-3">DiveForge Software License Agreement</h4>
                            <p class="text-gray-600 mb-3">
                                By installing and using DiveForge, you agree to the following terms and conditions:
                            </p>
                            <ul class="text-gray-600 space-y-2 list-disc list-inside">
                                <li>This software is licensed, not sold, to you for use only under the terms of this license.</li>
                                <li>You may use this software for commercial dive shop operations.</li>
                                <li>You may not redistribute, sublicense, or sell copies of this software.</li>
                                <li>The software is provided "as is" without warranty of any kind.</li>
                                <li>You agree to comply with all applicable laws and regulations in your use of this software.</li>
                                <li>Customer data privacy and security are your responsibility as the operator.</li>
                                <li>Regular backups of your data are strongly recommended.</li>
                            </ul>
                            <p class="text-gray-600 mt-3">
                                For complete terms and conditions, please refer to the full license documentation.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Installation Form -->
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <div>
                                <h4 class="font-semibold text-red-800">Installation Error</h4>
                                <p class="text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('installer.step1.process') }}" id="welcomeForm">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- License Acceptance -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="license_accepted" name="license_accepted" value="1" 
                                   class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   {{ old('license_accepted') ? 'checked' : '' }}>
                            <label for="license_accepted" class="text-sm text-gray-700">
                                <span class="font-medium">I accept the license agreement</span> and understand the terms and conditions for using DiveForge.
                            </label>
                        </div>
                        @error('license_accepted')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Requirements Confirmation -->
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" id="requirements_met" name="requirements_met" value="1" 
                                   class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   {{ old('requirements_met') || $system_check['all_passed'] ? 'checked' : '' }}
                                   {{ !$system_check['all_passed'] ? 'disabled' : '' }}>
                            <label for="requirements_met" class="text-sm text-gray-700">
                                <span class="font-medium">All system requirements are met</span> and I'm ready to proceed with the installation.
                            </label>
                        </div>
                        @error('requirements_met')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Installation will take approximately 2-5 minutes
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="checkRequirements()" 
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Recheck Requirements
                            </button>
                            <button type="submit" id="continueBtn"
                                    class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ !$system_check['all_passed'] ? 'disabled' : '' }}>
                                <span class="flex items-center">
                                    Continue to Database Setup
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- System Information Footer -->
        <div class="mt-8 text-center text-white text-sm">
            <div class="flex items-center justify-center space-x-8">
                <div>
                    <i class="fab fa-php mr-1"></i>
                    PHP {{ $php_version }}
                </div>
                <div>
                    <i class="fas fa-code mr-1"></i>
                    Laravel {{ $laravel_version }}
                </div>
                <div>
                    <i class="fas fa-water mr-1"></i>
                    DiveForge v1.0.0
                </div>
            </div>
            <div class="mt-4 text-blue-100">
                © {{ date('Y') }} DiveForge. Making waves in dive shop management.
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('welcomeForm');
            const licenseCheckbox = document.getElementById('license_accepted');
            const requirementsCheckbox = document.getElementById('requirements_met');
            const continueBtn = document.getElementById('continueBtn');

            function updateButtonState() {
                const allChecked = licenseCheckbox.checked && requirementsCheckbox.checked;
                continueBtn.disabled = !allChecked;
                
                if (allChecked) {
                    continueBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    continueBtn.classList.add('pulse-animation');
                } else {
                    continueBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    continueBtn.classList.remove('pulse-animation');
                }
            }

            licenseCheckbox.addEventListener('change', updateButtonState);
            requirementsCheckbox.addEventListener('change', updateButtonState);
            
            // Initial state
            updateButtonState();

            // Form submission handling
            form.addEventListener('submit', function(e) {
                if (!licenseCheckbox.checked || !requirementsCheckbox.checked) {
                    e.preventDefault();
                    showNotification('Please accept the license agreement and confirm system requirements.', 'error');
                    return;
                }

                // Show loading state
                continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing Installation...';
                continueBtn.disabled = true;
            });
        });

        // Check requirements via AJAX
        function checkRequirements() {
            const button = event.target;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';
            button.disabled = true;

            fetch('{{ route("installer.check.requirements") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Reload page to show updated requirements
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to check requirements. Please refresh the page.', 'error');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'error' ? 'bg-red-500' : 'bg-blue-500'} text-white`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Smooth scrolling for any anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>