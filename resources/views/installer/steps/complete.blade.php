<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DiveForge Installation Complete</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .success-icon {
            animation: bounceIn 1s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .celebration {
            animation: celebration 3s ease-in-out infinite;
        }
        @keyframes celebration {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
        }
        .float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .pulse-green {
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0%, 100% { background-color: #10b981; }
            50% { background-color: #059669; }
        }
        .confetti {
            position: fixed;
            top: -10px;
            width: 10px;
            height: 10px;
            background: #f39c12;
            animation: confetti-fall 3s linear infinite;
        }
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1);
        }
        .next-step {
            transition: all 0.3s ease;
        }
        .next-step:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
        }
        .login-button {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }
        .login-button:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }
        .stats-counter {
            animation: countUp 2s ease-out;
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Confetti Animation -->
    <div id="confetti-container"></div>

    <!-- Success Header -->
    <div class="text-center py-16">
        <div class="max-w-4xl mx-auto px-6">
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-white rounded-full shadow-2xl success-icon">
                    <i class="fas fa-check text-6xl text-green-500"></i>
                </div>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
                <span class="celebration">🎉</span> Success! <span class="celebration">🎉</span>
            </h1>
            <p class="text-2xl md:text-3xl text-green-100 mb-4">
                DiveForge has been installed successfully!
            </p>
            <p class="text-lg text-green-200 max-w-2xl mx-auto">
                Your dive shop management system is ready to help you streamline operations, manage equipment, and grow your business.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 pb-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content Panel -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Installation Summary -->
                <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 px-8 py-6 border-b">
                        <h2 class="text-2xl font-bold text-green-800 flex items-center">
                            <i class="fas fa-clipboard-list text-green-600 mr-3"></i>
                            Installation Summary
                        </h2>
                        <p class="text-green-700 mt-2">Your DiveForge installation completed successfully with the following configuration:</p>
                    </div>
                    
                    <div class="p-8">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Installation Details -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Installation ID:</span>
                                    <span class="text-gray-900 font-mono text-sm">{{ $installation_data['installation_id'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Completed At:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($installation_data['completed_at'] ?? now())->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">DiveForge Version:</span>
                                    <span class="text-gray-900">{{ $installation_data['version'] ?? 'v1.0.0' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Database Type:</span>
                                    <span class="text-gray-900">{{ strtoupper($installation_data['database_type'] ?? 'Unknown') }}</span>
                                </div>
                            </div>
                            
                            <!-- Business Details -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Dive Shop:</span>
                                    <span class="text-gray-900">{{ $installation_data['shop_name'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Administrator:</span>
                                    <span class="text-gray-900">{{ $installation_data['admin_email'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">PHP Version:</span>
                                    <span class="text-gray-900">{{ $installation_data['php_version'] ?? PHP_VERSION }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Laravel Version:</span>
                                    <span class="text-gray-900">{{ $installation_data['laravel_version'] ?? app()->version() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Installation Time -->
                        @if(isset($installation_data['installation_time']))
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-stopwatch text-blue-600"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-800">Installation Time</h4>
                                    <p class="text-blue-700 text-sm">
                                        Completed in {{ gmdate('i:s', $installation_data['installation_time']) }} 
                                        ({{ $installation_data['installation_time'] }} seconds)
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Available Features -->
                <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-8 py-6 border-b">
                        <h2 class="text-2xl font-bold text-blue-800 flex items-center">
                            <i class="fas fa-magic text-blue-600 mr-3"></i>
                            Features Now Available
                        </h2>
                        <p class="text-blue-700 mt-2">Explore the powerful features that are now ready to use in your DiveForge installation:</p>
                    </div>
                    
                    <div class="p-8">
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="feature-card p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-swimming-pool text-white text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-blue-800">Equipment Management</h3>
                                </div>
                                <p class="text-blue-700 text-sm">Track rental equipment, maintenance schedules, and inventory levels with ease.</p>
                            </div>
                            
                            <div class="feature-card p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-green-800">Course Scheduling</h3>
                                </div>
                                <p class="text-green-700 text-sm">Manage diving courses, instructor schedules, and student enrollments seamlessly.</p>
                            </div>
                            
                            <div class="feature-card p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-certificate text-white text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-purple-800">Certification Tracking</h3>
                                </div>
                                <p class="text-purple-700 text-sm">Issue digital certificates and track student progress through certification levels.</p>
                            </div>
                            
                            <div class="feature-card p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-yellow-800">Customer Management</h3>
                                </div>
                                <p class="text-yellow-700 text-sm">Maintain detailed customer profiles, diving history, and communication preferences.</p>
                            </div>
                            
                            <div class="feature-card p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-bar text-white text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-red-800">Business Analytics</h3>
                                </div>
                                <p class="text-red-700 text-sm">Generate comprehensive reports on revenue, equipment usage, and business performance.</p>
                            </div>
                            
                            <div class="feature-card p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl border border-indigo-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-mobile-alt text-white text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-indigo-800">Mobile Ready</h3>
                                </div>
                                <p class="text-indigo-700 text-sm">Access your dive shop management system from anywhere with responsive mobile design.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-tachometer-alt text-green-600 mr-3"></i>
                            Ready to Dive In!
                        </h2>
                        
                        <div class="grid sm:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                                <div class="text-3xl font-bold text-green-600 stats-counter">0</div>
                                <div class="text-green-800 font-medium mt-2">Equipment Items</div>
                                <div class="text-green-600 text-sm">Ready to be added</div>
                            </div>
                            
                            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 stats-counter">0</div>
                                <div class="text-blue-800 font-medium mt-2">Active Courses</div>
                                <div class="text-blue-600 text-sm">Waiting to be created</div>
                            </div>
                            
                            <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                                <div class="text-3xl font-bold text-purple-600 stats-counter">1</div>
                                <div class="text-purple-800 font-medium mt-2">Administrator</div>
                                <div class="text-purple-600 text-sm">You're all set!</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <!-- Login Action -->
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <div class="p-8 text-center">
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                    <i class="fas fa-sign-in-alt text-blue-600 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Access Your Dashboard</h3>
                                <p class="text-gray-600 text-sm">Log in to your DiveForge admin panel and start managing your dive shop.</p>
                            </div>
                            
                            <a href="{{ $login_url ?? route('login') }}" 
                               class="login-button w-full py-4 px-6 text-white rounded-xl font-semibold text-lg inline-block mb-4">
                                <i class="fas fa-rocket mr-2"></i>
                                Launch DiveForge
                            </a>
                            
                            <div class="text-xs text-gray-500">
                                Use your administrator email and password to log in
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h3 class="font-bold text-gray-800 flex items-center">
                                <i class="fas fa-list-check text-indigo-600 mr-2"></i>
                                Next Steps
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="next-step flex items-start space-x-3 p-3 rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm">Complete Shop Profile</h4>
                                        <p class="text-gray-600 text-xs">Add your logo, complete business details, and customize settings.</p>
                                    </div>
                                </div>
                                
                                <div class="next-step flex items-start space-x-3 p-3 rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm">Add Equipment Inventory</h4>
                                        <p class="text-gray-600 text-xs">Start by adding your rental equipment and gear inventory.</p>
                                    </div>
                                </div>
                                
                                <div class="next-step flex items-start space-x-3 p-3 rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm">Create Your First Course</h4>
                                        <p class="text-gray-600 text-xs">Set up diving courses, schedules, and instructor assignments.</p>
                                    </div>
                                </div>
                                
                                <div class="next-step flex items-start space-x-3 p-3 rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 w-6 h-6 bg-yellow-500 text-white rounded-full flex items-center justify-center text-xs font-bold">4</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm">Invite Team Members</h4>
                                        <p class="text-gray-600 text-xs">Add instructors and staff members to your DiveForge system.</p>
                                    </div>
                                </div>
                                
                                <div class="next-step flex items-start space-x-3 p-3 rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold">5</div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm">Configure Payment Settings</h4>
                                        <p class="text-gray-600 text-xs">Set up payment processing and pricing for your services.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Resources -->
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h3 class="font-bold text-gray-800 flex items-center">
                                <i class="fas fa-life-ring text-orange-600 mr-2"></i>
                                Need Help?
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 text-sm">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-book text-blue-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Documentation</h4>
                                        <p class="text-gray-600 text-xs">Comprehensive guides and tutorials</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-video text-green-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Video Tutorials</h4>
                                        <p class="text-gray-600 text-xs">Step-by-step video walkthroughs</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-comments text-purple-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Community Forum</h4>
                                        <p class="text-gray-600 text-xs">Connect with other dive shop owners</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-envelope text-red-500 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Technical Support</h4>
                                        <p class="text-gray-600 text-xs">Direct support for technical issues</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Installation Badge -->
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <div class="p-6 text-center">
                            <div class="inline-flex items-center space-x-2 px-4 py-2 bg-green-100 rounded-full">
                                <i class="fas fa-certificate text-green-600"></i>
                                <span class="text-green-800 font-semibold text-sm">Successfully Installed</span>
                            </div>
                            <p class="text-gray-500 text-xs mt-2">{{ \Carbon\Carbon::parse($installation_data['completed_at'] ?? now())->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center py-8 text-green-100">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-center space-x-8 text-sm mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-water"></i>
                    <span>DiveForge v{{ $installation_data['version'] ?? '1.0.0' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-code"></i>
                    <span>Laravel {{ $installation_data['laravel_version'] ?? app()->version() }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fab fa-php"></i>
                    <span>PHP {{ $installation_data['php_version'] ?? PHP_VERSION }}</span>
                </div>
            </div>
            <p class="text-green-200">
                © {{ date('Y') }} DiveForge. Making waves in dive shop management.
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
            animateCounters();
            initializeCelebration();
        });

        function createConfetti() {
            const confettiContainer = document.getElementById('confetti-container');
            const colors = ['#f39c12', '#e74c3c', '#9b59b6', '#3498db', '#2ecc71', '#f1c40f'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 3 + 's';
                    confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
                    confettiContainer.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 6000);
                }, i * 100);
            }
        }

        function animateCounters() {
            const counters = document.querySelectorAll('.stats-counter');
            counters.forEach((counter, index) => {
                const target = parseInt(counter.textContent);
                let current = 0;
                const increment = target > 0 ? Math.ceil(target / 50) : 1;
                
                setTimeout(() => {
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        counter.textContent = current;
                    }, 40);
                }, index * 200);
            });
        }

        function initializeCelebration() {
            // Add pulse animation to login button
            const loginButton = document.querySelector('.login-button');
            if (loginButton) {
                setTimeout(() => {
                    loginButton.classList.add('pulse-green');
                }, 2000);
            }
            
            // Auto-scroll to login button after celebrations
            setTimeout(() => {
                if (loginButton) {
                    loginButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 4000);
        }

        // Feature card interactions
        document.addEventListener('DOMContentLoaded', function() {
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) rotate(1deg)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-5px) rotate(0deg)';
                });
            });
        });

        // Next steps interactions
        document.addEventListener('DOMContentLoaded', function() {
            const nextSteps = document.querySelectorAll('.next-step');
            nextSteps.forEach((step, index) => {
                step.addEventListener('click', function() {
                    // Add a check mark animation
                    const number = this.querySelector('.w-6.h-6');
                    const originalContent = number.innerHTML;
                    
                    number.innerHTML = '<i class="fas fa-check text-xs"></i>';