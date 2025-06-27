<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DiveForge Installation - Ready to Install</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .finish-icon {
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
        .installation-progress {
            transition: all 0.5s ease;
        }
        .progress-step {
            transition: all 0.3s ease;
        }
        .progress-step.active {
            transform: scale(1.05);
        }
        .summary-card {
            transition: all 0.3s ease;
        }
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .install-button {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }
        .install-button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        }
        .install-button:disabled {
            background: #d1d5db;
            transform: none;
            box-shadow: none;
        }
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm text-green-600">Admin</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm text-green-600">Shop</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">5</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600">Finish</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Installation Panel -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-12 text-white text-center">
                        <div class="mb-6">
                            <i class="fas fa-flag-checkered text-6xl mb-4 finish-icon"></i>
                        </div>
                        <h1 class="text-4xl font-bold mb-4">Ready to Install DiveForge!</h1>
                        <p class="text-xl text-green-100 max-w-2xl mx-auto">
                            Everything is configured and ready. Click the install button below to complete your DiveForge setup.
                        </p>
                        <div class="mt-8 text-sm text-green-100">
                            <i class="fas fa-clock mr-2"></i>
                            Estimated installation time: {{ $estimated_time ?? '2-5 minutes' }}
                        </div>
                    </div>

                    <!-- Installation Content -->
                    <div class="p-8">
                        <!-- Installation Progress (Hidden initially) -->
                        <div id="installationProgress" class="mb-8 hidden">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Installing DiveForge</h2>
                                <p class="text-gray-600">Please wait while we set up your dive shop management system...</p>
                            </div>

                            <!-- Progress Steps -->
                            <div class="space-y-4" id="progressSteps">
                                <div class="progress-step flex items-center p-4 rounded-lg border" data-step="1">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4">
                                        <span class="step-number text-sm font-semibold text-gray-500">1</span>
                                        <i class="step-spinner fas fa-spinner spinner hidden text-blue-600"></i>
                                        <i class="step-check fas fa-check hidden text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Environment Configuration</h3>
                                        <p class="text-sm text-gray-600">Setting up database connection and environment variables</p>
                                    </div>
                                </div>

                                <div class="progress-step flex items-center p-4 rounded-lg border" data-step="2">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4">
                                        <span class="step-number text-sm font-semibold text-gray-500">2</span>
                                        <i class="step-spinner fas fa-spinner spinner hidden text-blue-600"></i>
                                        <i class="step-check fas fa-check hidden text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Database Migration</h3>
                                        <p class="text-sm text-gray-600">Creating database tables and structure</p>
                                    </div>
                                </div>

                                <div class="progress-step flex items-center p-4 rounded-lg border" data-step="3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4">
                                        <span class="step-number text-sm font-semibold text-gray-500">3</span>
                                        <i class="step-spinner fas fa-spinner spinner hidden text-blue-600"></i>
                                        <i class="step-check fas fa-check hidden text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Administrator Account</h3>
                                        <p class="text-sm text-gray-600">Creating your administrator user account</p>
                                    </div>
                                </div>

                                <div class="progress-step flex items-center p-4 rounded-lg border" data-step="4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4">
                                        <span class="step-number text-sm font-semibold text-gray-500">4</span>
                                        <i class="step-spinner fas fa-spinner spinner hidden text-blue-600"></i>
                                        <i class="step-check fas fa-check hidden text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Dive Shop Setup</h3>
                                        <p class="text-sm text-gray-600">Configuring your dive shop details and settings</p>
                                    </div>
                                </div>

                                <div class="progress-step flex items-center p-4 rounded-lg border" data-step="5">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4">
                                        <span class="step-number text-sm font-semibold text-gray-500">5</span>
                                        <i class="step-spinner fas fa-spinner spinner hidden text-blue-600"></i>
                                        <i class="step-check fas fa-check hidden text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">Initial Data Setup</h3>
                                        <p class="text-sm text-gray-600">Loading default data and finalizing installation</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Overall Progress Bar -->
                            <div class="mt-8">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>Installation Progress</span>
                                    <span id="overallProgress">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div id="overallProgressBar" class="installation-progress h-3 rounded-full bg-green-500" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Pre-Installation Summary -->
                        <div id="preInstallation">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-clipboard-check text-green-600 mr-3"></i>
                                Installation Summary
                            </h2>
                            
                            <div class="grid md:grid-cols-2 gap-6 mb-8">
                                <!-- Database Configuration -->
                                <div class="summary-card p-6 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-database text-blue-600 text-xl"></i>
                                        <h3 class="font-semibold text-blue-800">Database Configuration</h3>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-blue-700">Type:</span>
                                            <span class="font-medium text-blue-800">{{ strtoupper($summary['database']['type']) }}</span>
                                        </div>
                                        @if($summary['database']['type'] !== 'sqlite')
                                            <div class="flex justify-between">
                                                <span class="text-blue-700">Host:</span>
                                                <span class="font-medium text-blue-800">{{ $summary['database']['host'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-blue-700">Port:</span>
                                                <span class="font-medium text-blue-800">{{ $summary['database']['port'] }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between">
                                            <span class="text-blue-700">Database:</span>
                                            <span class="font-medium text-blue-800">{{ $summary['database']['database'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Administrator Account -->
                                <div class="summary-card p-6 bg-purple-50 border border-purple-200 rounded-xl">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                                        <h3 class="font-semibold text-purple-800">Administrator Account</h3>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-purple-700">Name:</span>
                                            <span class="font-medium text-purple-800">{{ $summary['admin']['name'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-purple-700">Email:</span>
                                            <span class="font-medium text-purple-800">{{ $summary['admin']['email'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-purple-700">Role:</span>
                                            <span class="font-medium text-purple-800">Super Administrator</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dive Shop Details -->
                                <div class="summary-card p-6 bg-green-50 border border-green-200 rounded-xl">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-store text-green-600 text-xl"></i>
                                        <h3 class="font-semibold text-green-800">Dive Shop Details</h3>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Name:</span>
                                            <span class="font-medium text-green-800">{{ $summary['shop']['name'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Email:</span>
                                            <span class="font-medium text-green-800">{{ $summary['shop']['email'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Timezone:</span>
                                            <span class="font-medium text-green-800">{{ $summary['shop']['timezone'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-green-700">Currency:</span>
                                            <span class="font-medium text-green-800">{{ $summary['shop']['currency'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- System Status -->
                                <div class="summary-card p-6 bg-yellow-50 border border-yellow-200 rounded-xl">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-server text-yellow-600 text-xl"></i>
                                        <h3 class="font-semibold text-yellow-800">System Status</h3>
                                    </div>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-yellow-700">PHP Version:</span>
                                            <span class="font-medium text-yellow-800">{{ $summary['system']['php_version']['current'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-yellow-700">Extensions:</span>
                                            <span class="font-medium text-yellow-800">
                                                {{ collect($summary['system']['extensions'])->where('passed', true)->count() }}/{{ count($summary['system']['extensions']) }} Ready
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-yellow-700">Permissions:</span>
                                            <span class="font-medium text-yellow-800">
                                                {{ collect($summary['system']['permissions'])->where('passed', true)->count() }}/{{ count($summary['system']['permissions']) }} Ready
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-yellow-700">Overall Status:</span>
                                            <span class="font-medium {{ $summary['system']['all_passed'] ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $summary['system']['all_passed'] ? 'Ready' : 'Issues Found' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Features That Will Be Enabled -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-magic text-indigo-600 mr-2"></i>
                                    Features That Will Be Available
                                </h3>
                                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($summary['features_enabled'] as $feature)
                                        <div class="flex items-center space-x-2 p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                                            <i class="fas fa-check-circle text-indigo-600"></i>
                                            <span class="text-sm font-medium text-indigo-800">{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-xs text-gray-500">
                                    <strong>Advanced Modules:</strong>
                                    <ul class="list-disc ml-5">
                                        <li>Equipment Repair & Service Management</li>
                                        <li>Air & Gift Card Management</li>
                                        <li>Advanced Trip & Charter Management</li>
                                        <li>Flexible Course Scheduling & Materials</li>
                                        <li>Commission & Incentive Management</li>
                                        <li>Vendor Catalog & Advanced Inventory</li>
                                        <li>Customer Loyalty & Communication</li>
                                        <li>Financial Integrations & Reporting</li>
                                        <li>Automated Product Image Management</li>
                                        <li>Visual Inventory & AR/360° Support</li>
                                        <li>Local Dive Site Database & Maps</li>
                                        <li>Boat, Compressor, Nitrox, and Safety Ops</li>
                                        <li>Security Camera & Incident Analytics</li>
                                        <li>Plugin/Theme System & API-first Design</li>
                                    </ul>
                                    <span class="block mt-2">See <a href="/Developer_Guide.md" class="underline text-blue-600">Developer Guide</a> for full feature list.</span>
                                </div>
                            </div>

                            <!-- Final Checks -->
                            @if(!$summary['system']['all_passed'])
                                <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                                        <div>
                                            <h4 class="font-semibold text-red-800">System Requirements Warning</h4>
                                            <p class="text-red-700 text-sm">Some system requirements are not fully met. Installation may continue, but some features might not work correctly.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Installation Actions -->
                            <div class="flex items-center justify-between">
                                <a href="{{ route('installer.step4.show') }}" 
                                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to Shop Setup
                                </a>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This will install and configure DiveForge
                                    </div>
                                    <form method="POST" action="{{ route('installer.finish') }}" id="installForm">
                                        @csrf
                                        <button type="submit" id="installBtn"
                                                class="install-button px-8 py-3 text-white rounded-lg font-medium transition-all">
                                            <span id="installBtnText" class="flex items-center">
                                                <i class="fas fa-rocket mr-2"></i>
                                                Install DiveForge
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Installation Error Display -->
                        <div id="installationError" class="hidden">
                            <div class="p-6 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center space-x-3 mb-4">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                    <h3 class="font-semibold text-red-800">Installation Failed</h3>
                                </div>
                                <div id="errorMessage" class="text-red-700 mb-4"></div>
                                <div class="flex items-center space-x-4">
                                    <button onclick="retryInstallation()" 
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                                        <i class="fas fa-redo mr-2"></i>
                                        Retry Installation
                                    </button>
                                    <a href="{{ route('installer.step2.show') }}" 
                                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Back to Database Setup
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Installation Tips Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <!-- Installation Tips -->
                    <div class="bg-white rounded-2xl card-shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Installation Tips
                        </h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <span>Keep this browser tab open during installation</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-wifi text-green-500 mt-0.5"></i>
                                <span>Ensure stable internet connection</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-clock text-purple-500 mt-0.5"></i>
                                <span>Installation typically takes 2-5 minutes</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-shield-alt text-red-500 mt-0.5"></i>
                                <span>Don't refresh the page during installation</span>
                            </div>
                        </div>
                    </div>

                    <!-- What Happens Next -->
                    <div class="bg-white rounded-2xl card-shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-list-ol text-indigo-500 mr-2"></i>
                            What Happens Next
                        </h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span>Database tables will be created</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span>Your administrator account will be set up</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span>Dive shop configuration will be saved</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span>Initial system data will be loaded</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span>You'll be redirected to the login page</span>
                            </div>
                        </div>
                    </div>

                    <!-- Support Information -->
                    <div class="bg-white rounded-2xl card-shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-life-ring text-orange-500 mr-2"></i>
                            Need Help?
                        </h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <p>If you encounter any issues during installation:</p>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-book text-blue-500 mt-0.5"></i>
                                <span>Check the installation documentation</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-bug text-red-500 mt-0.5"></i>
                                <span>Review server error logs</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-envelope text-green-500 mt-0.5"></i>
                                <span>Contact technical support</span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Information -->
                    <div class="bg-white rounded-2xl card-shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-cog text-gray-500 mr-2"></i>
                            Technical Details
                        </h3>
                        <div class="space-y-2 text-xs text-gray-500">
                            <div class="flex justify-between">
                                <span>DiveForge Version:</span>
                                <span>v1.0.0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Laravel Version:</span>
                                <span>{{ app()->version() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>PHP Version:</span>
                                <span>{{ PHP_VERSION }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Installation ID:</span>
                                <span>{{ Str::random(8) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>