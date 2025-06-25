<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DiveForge Installation - Shop Setup</title>
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
        .shop-icon {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
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
        .timezone-option {
            transition: all 0.2s ease;
        }
        .timezone-option:hover {
            background-color: #f3f4f6;
        }
        .currency-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .currency-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .currency-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .preview-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        .completion-progress {
            transition: width 0.3s ease;
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
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">4</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600">Shop</span>
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
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-12 text-white text-center">
                        <div class="mb-6">
                            <i class="fas fa-store text-6xl mb-4 shop-icon"></i>
                        </div>
                        <h1 class="text-4xl font-bold mb-4">Dive Shop Configuration</h1>
                        <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                            Set up your dive shop details to customize DiveForge for your business. This information will appear on invoices, certificates, and throughout the system.
                        </p>
                        <div class="mt-8 flex items-center justify-center space-x-8 text-sm">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-certificate text-blue-200"></i>
                                <span>Certification Ready</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-globe text-blue-200"></i>
                                <span>Multi-location</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-palette text-blue-200"></i>
                                <span>Customizable</span>
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
                                        <h4 class="font-semibold text-red-800">Configuration Error</h4>
                                        <p class="text-red-700">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Completion Progress -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>Setup Progress</span>
                                <span id="progressText">0% Complete</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="completion-progress h-2 rounded-full bg-blue-500" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Shop Configuration Form -->
                        <form method="POST" action="{{ route('installer.step4.process') }}" id="shopForm">
                            @csrf
                            
                            <!-- Basic Information Section -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                                    Basic Information
                                </h2>
                                
                                <div class="space-y-6">
                                    <!-- Shop Name -->
                                    <div class="input-group">
                                        <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-store mr-1"></i>Dive Shop Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="shop_name" id="shop_name" 
                                               value="{{ old('shop_name') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="Enter your dive shop name" required>
                                        <p class="text-xs text-gray-500 mt-1">This will appear on certificates, invoices, and throughout the system</p>
                                        @error('shop_name')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Shop Email -->
                                    <div class="input-group">
                                        <label for="shop_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-envelope mr-1"></i>Business Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="shop_email" id="shop_email" 
                                               value="{{ old('shop_email') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="info@yourdiveshop.com" required>
                                        <p class="text-xs text-gray-500 mt-1">Primary email for customer communications and system notifications</p>
                                        @error('shop_email')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-address-card text-blue-600 mr-3"></i>
                                    Contact Information
                                </h2>
                                
                                <div class="space-y-6">
                                    <!-- Phone Number -->
                                    <div class="input-group">
                                        <label for="shop_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-phone mr-1"></i>Phone Number
                                        </label>
                                        <input type="tel" name="shop_phone" id="shop_phone" 
                                               value="{{ old('shop_phone') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="+1 (555) 123-4567">
                                        <p class="text-xs text-gray-500 mt-1">Include country code for international numbers</p>
                                        @error('shop_phone')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Business Address -->
                                    <div class="input-group">
                                        <label for="shop_address" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-map-marker-alt mr-1"></i>Business Address
                                        </label>
                                        <textarea name="shop_address" id="shop_address" rows="3"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical"
                                                  placeholder="123 Ocean Drive&#10;Dive City, State 12345&#10;Country">{{ old('shop_address') }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">Full business address for certificates and legal documents</p>
                                        @error('shop_address')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Website -->
                                    <div class="input-group">
                                        <label for="shop_website" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-globe mr-1"></i>Website URL
                                        </label>
                                        <input type="url" name="shop_website" id="shop_website" 
                                               value="{{ old('shop_website') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                               placeholder="https://www.yourdiveshop.com">
                                        <p class="text-xs text-gray-500 mt-1">Your dive shop's website (include https://)</p>
                                        @error('shop_website')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Regional Settings Section -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-cog text-blue-600 mr-3"></i>
                                    Regional Settings
                                </h2>
                                
                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Timezone Selection -->
                                    <div class="input-group">
                                        <label for="shop_timezone" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-clock mr-1"></i>Timezone <span class="text-red-500">*</span>
                                        </label>
                                        <select name="shop_timezone" id="shop_timezone" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <option value="">Select your timezone</option>
                                            @foreach($timezones as $timezone)
                                                <option value="{{ $timezone }}" 
                                                        {{ old('shop_timezone') == $timezone ? 'selected' : '' }}
                                                        data-offset="{{ \Carbon\Carbon::now($timezone)->format('P') }}">
                                                    {{ $timezone }} ({{ \Carbon\Carbon::now($timezone)->format('P') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">All booking times and schedules will use this timezone</p>
                                        <div id="timezonePreview" class="mt-2 text-sm text-blue-600 hidden">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <span id="timezoneTime"></span>
                                        </div>
                                        @error('shop_timezone')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Currency Selection -->
                                    <div class="input-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-dollar-sign mr-1"></i>Currency <span class="text-red-500">*</span>
                                        </label>
                                        <input type="hidden" name="shop_currency" id="selectedCurrency" value="{{ old('shop_currency', 'USD') }}">
                                        
                                        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-2">
                                            @foreach($currencies as $code)
                                                @php
                                                    $currencyNames = [
                                                        'USD' => ['US Dollar', '$'],
                                                        'EUR' => ['Euro', '€'],
                                                        'GBP' => ['British Pound', '£'],
                                                        'JPY' => ['Japanese Yen', '¥'],
                                                        'CAD' => ['Canadian Dollar', 'C$'],
                                                        'AUD' => ['Australian Dollar', 'A$'],
                                                        'CHF' => ['Swiss Franc', 'CHF'],
                                                        'CNY' => ['Chinese Yuan', '¥'],
                                                        'SEK' => ['Swedish Krona', 'kr'],
                                                        'NZD' => ['New Zealand Dollar', 'NZ$']
                                                    ];
                                                    $name = $currencyNames[$code][0] ?? $code;
                                                    $symbol = $currencyNames[$code][1] ?? $code;
                                                @endphp
                                                <div class="currency-card p-2 border rounded cursor-pointer {{ old('shop_currency', 'USD') == $code ? 'selected' : '' }}" 
                                                     data-currency="{{ $code }}" onclick="selectCurrency('{{ $code }}')">
                                                    <div class="text-center">
                                                        <div class="font-semibold text-gray-800">{{ $symbol }}</div>
                                                        <div class="text-xs text-gray-600">{{ $code }}</div>
                                                        <div class="text-xs text-gray-500">{{ $name }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">All prices and financial reports will use this currency</p>
                                        @error('shop_currency')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Business Features Section -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                    <i class="fas fa-star text-blue-600 mr-3"></i>
                                    What You'll Get
                                </h2>
                                
                                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-swimming-pool text-blue-600 text-xl"></i>
                                            <div>
                                                <h3 class="font-semibold text-blue-800">Equipment Management</h3>
                                                <p class="text-sm text-blue-600">Track rental gear, maintenance schedules</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                                            <div>
                                                <h3 class="font-semibold text-green-800">Course Scheduling</h3>
                                                <p class="text-sm text-green-600">Manage classes, instructors, students</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-certificate text-purple-600 text-xl"></i>
                                            <div>
                                                <h3 class="font-semibold text-purple-800">Certification Tracking</h3>
                                                <p class="text-sm text-purple-600">Digital certificates, progress tracking</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-users text-yellow-600 text-xl"></i>
                                            <div>
                                                <h3 class="font-semibold text-yellow-800">Customer Management</h3>
                                                <p class="text-sm text-yellow-600">Customer profiles, booking history</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-chart-bar text-red-600 text-xl"></i>
                                            <div>
                                                <h3 class="font-semibold text-red-800">Business Analytics</h3>
                                                <p class="text-sm text-red-600">Revenue reports, performance metrics</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-mobile-alt text-indigo-600 text-xl"></i>
                                            <div>
                                                <h3 class="font-semibold text-indigo-800">Mobile Ready</h3>
                                                <p class="text-sm text-indigo-600">Access anywhere, responsive design</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <a href="{{ route('installer.step3.show') }}" 
                                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Back to Administrator
                                </a>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Almost ready to launch!
                                    </div>
                                    <button type="submit" id="continueBtn"
                                            class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            disabled>
                                        <span class="flex items-center">
                                            Complete Installation
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Live Preview Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <!-- Shop Preview Card -->
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <div class="preview-card p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-eye text-blue-600 mr-2"></i>
                                Live Preview
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="p-4 bg-white rounded-lg border">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-store text-blue-600 text-2xl"></i>
                                        </div>
                                        <h4 id="previewShopName" class="font-bold text-gray-800 text-lg">Your Dive Shop</h4>
                                        <p id="previewShopEmail" class="text-gray-600 text-sm">info@yourdiveshop.com</p>
                                        <div class="mt-3 space-y-1 text-xs text-gray-500">
                                            <div id="previewPhone" class="hidden">
                                                <i class="fas fa-phone mr-1"></i>
                                                <span></span>
                                            </div>
                                            <div id="previewWebsite" class="hidden">
                                                <i class="fas fa-globe mr-1"></i>
                                                <a href="#" class="text-blue-600 hover:underline" target="_blank"></a>
                                            </div>
                                            <div id="previewAddress" class="hidden">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-white rounded-lg border">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Timezone:</span>
                                        <span id="previewTimezone" class="font-medium text-gray-800">Not selected</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm mt-2">
                                        <span class="text-gray-600">Currency:</span>
                                        <span id="previewCurrency" class="font-medium text-gray-800">USD ($)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="bg-white rounded-2xl card-shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Setup Tips
                        </h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Use your official business name for professional appearance</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Choose the timezone where most of your operations occur</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>All settings can be modified later in the admin panel</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Contact information helps build customer trust</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps Preview -->
                    <div class="bg-white rounded-2xl card-shadow p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-rocket text-purple-500 mr-2"></i>
                            After Installation
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span>Add your first equipment items</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span>Create diving courses