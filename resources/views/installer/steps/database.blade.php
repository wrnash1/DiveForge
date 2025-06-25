<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DiveForge Installation - Database Configuration</title>
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
        .connection-status {
            transition: all 0.5s ease;
        }
        .pulse-dot {
            animation: pulse-dot 1.5s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
        }
        .database-icon {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .test-animation {
            animation: spin 1s linear infinite;
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
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm text-green-600">Welcome</span>
                    </div>
                    <div class="w-8 h-0.5 bg-green-500"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">2</span>
                        </div>
                        <span class="text-sm font-medium text-blue-600">Database</span>
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
                    <i class="fas fa-database text-6xl mb-4 database-icon"></i>
                </div>
                <h1 class="text-4xl font-bold mb-4">Database Configuration</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Let's connect your DiveForge installation to a database where all your dive shop data will be stored securely.
                </p>
                <div class="mt-8 flex items-center justify-center space-x-8 text-sm">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-blue-200"></i>
                        <span>Secure Storage</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sync-alt text-blue-200"></i>
                        <span>Real-time Testing</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-database text-blue-200"></i>
                        <span>Multiple DB Types</span>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <!-- Connection Status Alert -->
                <div id="connectionAlert" class="mb-6 p-4 rounded-lg border hidden">
                    <div class="flex items-center space-x-3">
                        <div id="connectionIcon"></div>
                        <div>
                            <h4 id="connectionTitle" class="font-semibold"></h4>
                            <p id="connectionMessage" class="text-sm"></p>
                        </div>
                    </div>
                </div>

                <!-- Error Messages -->
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            <div>
                                <h4 class="font-semibold text-red-800">Database Connection Error</h4>
                                <p class="text-red-700">{{ session('error') }}</p>
                                @if(session('connection_details'))
                                    <details class="mt-2">
                                        <summary class="cursor-pointer text-sm text-red-600 hover:text-red-800">View technical details</summary>
                                        <pre class="mt-2 text-xs bg-red-100 p-2 rounded overflow-x-auto">{{ json_encode(session('connection_details'), JSON_PRETTY_PRINT) }}</pre>
                                    </details>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Database Type Selection -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-server text-blue-600 mr-3"></i>
                        Choose Your Database Type
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Select the type of database you want to use for your DiveForge installation. Each option has different requirements and capabilities.
                    </p>

                    <div class="grid md:grid-cols-3 gap-4">
                        @foreach($supported_databases as $key => $name)
                            <div class="database-option relative cursor-pointer" data-database="{{ $key }}">
                                <input type="radio" name="db_connection" value="{{ $key }}" id="db_{{ $key }}" 
                                       class="sr-only" {{ old('db_connection', 'mysql') == $key ? 'checked' : '' }}>
                                <label for="db_{{ $key }}" class="block p-6 border-2 rounded-xl transition-all hover:border-blue-300 hover:shadow-md">
                                    <div class="text-center">
                                        <div class="mb-4">
                                            @if($key === 'mysql')
                                                <i class="fas fa-database text-4xl text-orange-500"></i>
                                            @elseif($key === 'pgsql')
                                                <i class="fas fa-elephant text-4xl text-blue-600"></i>
                                            @else
                                                <i class="fas fa-file-alt text-4xl text-gray-600"></i>
                                            @endif
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            @if($key === 'mysql')
                                                Popular, reliable, and widely supported database system.
                                            @elseif($key === 'pgsql')
                                                Advanced open-source database with powerful features.
                                            @else
                                                Simple file-based database, perfect for testing.
                                            @endif
                                        </p>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('db_connection')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Database Configuration Form -->
                <form method="POST" action="{{ route('installer.step2.process') }}" id="databaseForm">
                    @csrf
                    <input type="hidden" name="db_connection" id="selectedDatabase" value="{{ old('db_connection', 'mysql') }}">

                    <!-- MySQL/PostgreSQL Configuration -->
                    <div id="sqlConfig" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Database Host -->
                            <div class="input-group">
                                <label for="db_host" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-server mr-1"></i>Database Host
                                </label>
                                <input type="text" name="db_host" id="db_host" 
                                       value="{{ old('db_host', 'localhost') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="localhost or IP address">
                                <p class="text-xs text-gray-500 mt-1">Usually 'localhost' or '127.0.0.1' for local installations</p>
                                @error('db_host')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Database Port -->
                            <div class="input-group">
                                <label for="db_port" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-plug mr-1"></i>Database Port
                                </label>
                                <input type="number" name="db_port" id="db_port" 
                                       value="{{ old('db_port', '3306') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="3306" min="1" max="65535">
                                <p class="text-xs text-gray-500 mt-1">MySQL: 3306, PostgreSQL: 5432</p>
                                @error('db_port')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Database Name -->
                            <div class="input-group">
                                <label for="db_database" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-database mr-1"></i>Database Name
                                </label>
                                <input type="text" name="db_database" id="db_database" 
                                       value="{{ old('db_database', 'diveforge') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="diveforge" required>
                                <p class="text-xs text-gray-500 mt-1">The database must already exist on your server</p>
                                @error('db_database')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Database Username -->
                            <div class="input-group">
                                <label for="db_username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-1"></i>Database Username
                                </label>
                                <input type="text" name="db_username" id="db_username" 
                                       value="{{ old('db_username', 'root') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Database username" required>
                                <p class="text-xs text-gray-500 mt-1">User must have CREATE, ALTER, INSERT, SELECT permissions</p>
                                @error('db_username')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Database Password -->
                        <div class="input-group">
                            <label for="db_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-1"></i>Database Password
                            </label>
                            <div class="relative">
                                <input type="password" name="db_password" id="db_password" 
                                       value="{{ old('db_password') }}"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Database password (leave empty if none)">
                                <button type="button" onclick="togglePassword('db_password')" 
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="db_password_icon"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Leave empty if your database user doesn't require a password</p>
                            @error('db_password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SQLite Configuration -->
                    <div id="sqliteConfig" class="space-y-6 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                <div>
                                    <h3 class="font-semibold text-blue-800 mb-2">SQLite Configuration</h3>
                                    <p class="text-blue-700 text-sm mb-4">
                                        SQLite is a file-based database that doesn't require a separate database server. 
                                        It's perfect for small installations and testing.
                                    </p>
                                    <div class="space-y-2 text-sm text-blue-600">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>No database server required</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-green-500"></i>
                                            <span>Automatic database creation</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                            <span>Not recommended for high-traffic sites</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="db_database_sqlite" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-alt mr-1"></i>Database File Path
                            </label>
                            <input type="text" name="db_database_sqlite" id="db_database_sqlite" 
                                   value="{{ old('db_database', 'diveforge.sqlite') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="diveforge.sqlite">
                            <p class="text-xs text-gray-500 mt-1">File will be created in your database directory</p>
                        </div>
                    </div>

                    <!-- Test Connection Section -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-xl border">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Test Database Connection</h3>
                                <p class="text-gray-600 text-sm">Verify your database settings before proceeding with the installation.</p>
                            </div>
                            <button type="button" onclick="testConnection()" id="testBtn"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plug mr-2"></i>
                                Test Connection
                            </button>
                        </div>

                        <!-- Connection Status -->
                        <div id="connectionStatus" class="mt-4 hidden">
                            <div class="flex items-center space-x-3 p-3 rounded-lg">
                                <div id="statusIcon" class="flex-shrink-0"></div>
                                <div class="flex-1">
                                    <div id="statusTitle" class="font-medium"></div>
                                    <div id="statusMessage" class="text-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Database Creation Help -->
                    <div class="mt-8">
                        <details class="bg-gray-50 rounded-lg">
                            <summary class="px-6 py-4 cursor-pointer font-medium text-gray-800 hover:text-blue-600 transition-colors">
                                <i class="fas fa-question-circle mr-2"></i>
                                Need help creating a database? Click here for instructions
                            </summary>
                            <div class="px-6 pb-6">
                                <div class="grid md:grid-cols-2 gap-6 mt-4">
                                    <!-- MySQL Instructions -->
                                    <div class="p-4 bg-white rounded-lg border">
                                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                            <i class="fas fa-database text-orange-500 mr-2"></i>
                                            MySQL Database
                                        </h4>
                                        <div class="text-sm text-gray-600 space-y-2">
                                            <p>1. Log into your MySQL server:</p>
                                            <code class="block bg-gray-100 p-2 rounded">mysql -u root -p</code>
                                            <p>2. Create the database:</p>
                                            <code class="block bg-gray-100 p-2 rounded">CREATE DATABASE diveforge;</code>
                                            <p>3. Create a user (optional):</p>
                                            <code class="block bg-gray-100 p-2 rounded">CREATE USER 'diveforge'@'localhost' IDENTIFIED BY 'password';</code>
                                            <p>4. Grant permissions:</p>
                                            <code class="block bg-gray-100 p-2 rounded">GRANT ALL PRIVILEGES ON diveforge.* TO 'diveforge'@'localhost';</code>
                                        </div>
                                    </div>

                                    <!-- PostgreSQL Instructions -->
                                    <div class="p-4 bg-white rounded-lg border">
                                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                            <i class="fas fa-elephant text-blue-600 mr-2"></i>
                                            PostgreSQL Database
                                        </h4>
                                        <div class="text-sm text-gray-600 space-y-2">
                                            <p>1. Log into PostgreSQL:</p>
                                            <code class="block bg-gray-100 p-2 rounded">psql -U postgres</code>
                                            <p>2. Create the database:</p>
                                            <code class="block bg-gray-100 p-2 rounded">CREATE DATABASE diveforge;</code>
                                            <p>3. Create a user (optional):</p>
                                            <code class="block bg-gray-100 p-2 rounded">CREATE USER diveforge WITH PASSWORD 'password';</code>
                                            <p>4. Grant permissions:</p>
                                            <code class="block bg-gray-100 p-2 rounded">GRANT ALL PRIVILEGES ON DATABASE diveforge TO diveforge;</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </details>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('installer.step1.show') }}" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Welcome
                        </a>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Connection will be tested before proceeding
                            </div>
                            <button type="submit" id="continueBtn"
                                    class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <span class="flex items-center">
                                    Continue to Administrator Setup
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });

        function initializePage() {
            // Initialize database type selection
            const databaseOptions = document.querySelectorAll('.database-option');
            const selectedDatabase = document.getElementById('selectedDatabase');
            
            databaseOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    selectedDatabase.value = radio.value;
                    updateDatabaseConfig(radio.value);
                    updateFormLabels();
                });
            });

            // Set initial state
            updateDatabaseConfig(selectedDatabase.value);
            updateFormLabels();
            
            // Update visual selection
            updateDatabaseSelection();

            // Auto-test connection when fields change (debounced)
            let testTimeout;
            const formInputs = document.querySelectorAll('#databaseForm input');
            formInputs.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(testTimeout);
                    testTimeout = setTimeout(() => {
                        if (validateFormInputs()) {
                            testConnection(true); // Silent test
                        }
                    }, 1000);
                });
            });

            // Form submission
            document.getElementById('databaseForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm();
            });
        }

        function updateDatabaseConfig(type) {
            const sqlConfig = document.getElementById('sqlConfig');
            const sqliteConfig = document.getElementById('sqliteConfig');
            const dbPortField = document.getElementById('db_port');
            
            if (type === 'sqlite') {
                sqlConfig.classList.add('hidden');
                sqliteConfig.classList.remove('hidden');
                
                // Update hidden field for SQLite
                const sqliteInput = document.getElementById('db_database_sqlite');
                document.getElementById('db_database').value = sqliteInput.value;
            } else {
                sqlConfig.classList.remove('hidden');
                sqliteConfig.classList.add('hidden');
                
                // Set default port based on database type
                if (type === 'mysql') {
                    dbPortField.value = dbPortField.value || '3306';
                } else if (type === 'pgsql') {
                    dbPortField.value = dbPortField.value || '5432';
                }
            }
            
            updateDatabaseSelection();
        }

        function updateDatabaseSelection() {
            const selectedValue = document.getElementById('selectedDatabase').value;
            const labels = document.querySelectorAll('.database-option label');
            
            labels.forEach(label => {
                const input = label.querySelector('input');
                if (input.value === selectedValue) {
                    label.classList.add('border-blue-500', 'bg-blue-50');
                    label.classList.remove('border-gray-300');
                } else {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-300');
                }
            });
        }

        function updateFormLabels() {
            const selectedType = document.getElementById('selectedDatabase').value;
            const portLabel = document.querySelector('label[for="db_port"]');
            const portInput = document.getElementById('db_port');
            const portHelp = portLabel.nextElementSibling.nextElementSibling;
            
            if (selectedType === 'mysql') {
                portInput.placeholder = '3306';
                portHelp.textContent = 'Default MySQL port is 3306';
            } else if (selectedType === 'pgsql') {
                portInput.placeholder = '5432';
                portHelp.textContent = 'Default PostgreSQL port is 5432';
            }
        }

        function testConnection(silent = false) {
            const testBtn = document.getElementById('testBtn');
            const originalText = testBtn.innerHTML;
            const formData = new FormData(document.getElementById('databaseForm'));
            
            // Update SQLite database field if needed
            if (formData.get('db_connection') === 'sqlite') {
                formData.set('db_database', document.getElementById('db_database_sqlite').value);
            }

            if (!silent) {
                testBtn.innerHTML = '<i class="fas fa-spinner test-animation mr-2"></i>Testing...';
                testBtn.disabled = true;
            }

            fetch('{{ route("installer.test.database") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                showConnectionStatus(data, silent);
            })
            .catch(error => {
                console.error('Error:', error);
                if (!silent) {
                    showConnectionStatus({
                        success: false,
                        message: 'Failed to test connection. Please check your network.',
                        details: { error: error.message }
                    });
                }
            })
            .finally(() => {
                if (!silent) {
                    testBtn.innerHTML = originalText;
                    testBtn.disabled = false;
                }
            });
        }

        function showConnectionStatus(data, silent = false) {
            const statusDiv = document.getElementById('connectionStatus');
            const statusIcon = document.getElementById('statusIcon');
            const statusTitle = document.getElementById('statusTitle');
            const statusMessage = document.getElementById('statusMessage');
            const alertDiv = document.getElementById('connectionAlert');
            
            if (!silent) {
                statusDiv.classList.remove('hidden');
            }
            
            if (data.success) {
                statusDiv.className = 'mt-4 flex items-center space-x-3 p-3 rounded-lg bg-green-50 border border-green-200';
                statusIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
                statusTitle.textContent = 'Connection Successful!';
                statusTitle.className = 'font-medium text-green-800';
                statusMessage.textContent = data.message || 'Database connection established successfully.';
                statusMessage.className = 'text-sm text-green-700';
                
                // Show success alert
                if (!silent) {
                    showAlert('success', 'Database Connected', data.message);
                }
                
                // Enable form submission
                document.getElementById('continueBtn').disabled = false;
                
            } else {
                statusDiv.className = 'mt-4 flex items-center space-x-3 p-3 rounded-lg bg-red-50 border border-red-200';
                statusIcon.innerHTML = '<i class="fas fa-times-circle text-red-500 text-xl"></i>';
                statusTitle.textContent = 'Connection Failed';
                statusTitle.className = 'font-medium text-red-800';
                statusMessage.innerHTML = data.message || 'Unable to connect to the database.';
                statusMessage.className = 'text-sm text-red-700';
                
                // Add technical details if available
                if (data.details && !silent) {
                    statusMessage.innerHTML += `<br><details class="mt-2"><summary class="cursor-pointer hover:text-red-800">Technical Details</summary><pre class="mt-1 text-xs bg-red-100 p-2 rounded overflow-x-auto">${JSON.stringify(data.details, null, 2)}</pre></details>`;
                }
                
                // Show error alert
                if (!silent) {
                    showAlert('error', 'Connection Failed', data.message);
                }
                
                // Disable form submission
                document.getElementById('continueBtn').disabled = true;
            }
        }

        function showAlert(type, title, message) {
            const alertDiv = document.getElementById('connectionAlert');
            const alertIcon = document.getElementById('connectionIcon');
            const alertTitle = document.getElementById('connectionTitle');
            const alertMessage = document.getElementById('connectionMessage');
            
            alertDiv.classList.remove('hidden', 'bg-green-50', 'bg-red-50', 'border-green-200', 'border-red-200');
            
            if (type === 'success') {
                alertDiv.classList.add('bg-green-50', 'border-green-200');
                alertIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                alertTitle.textContent = title;
                alertTitle.className = 'font-semibold text-green-800';
                alertMessage.textContent = message;
                alertMessage.className = 'text-sm text-green-700';
            } else {
                alertDiv.classList.add('bg-red-50', 'border-red-200');
                alertIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i>';
                alertTitle.textContent = title;
                alertTitle.className = 'font-semibold text-red-800';
                alertMessage.textContent = message;
                alertMessage.className = 'text-sm text-red-700';
            }
            
            // Auto-hide success alerts
            if (type === 'success') {
                setTimeout(() => {
                    alertDiv.classList.add('hidden');
                }, 5000);
            }
        }

        function validateFormInputs() {
            const dbType = document.getElementById('selectedDatabase').value;
            
            if (dbType === 'sqlite') {
                const dbPath = document.getElementById('db_database_sqlite').value;
                return dbPath.trim() !== '';
            } else {
                const host = document.getElementById('db_host').value;
                const port = document.getElementById('db_port').value;
                const database = document.getElementById('db_database').value;
                const username = document.getElementById('db_username').value;
                
                return host.trim() !== '' && port.trim() !== '' && 
                       database.trim() !== '' && username.trim() !== '';
            }
        }

        function submitForm() {
            const continueBtn = document.getElementById('continueBtn');
            const originalText = continueBtn.innerHTML;
            
            if (!validateFormInputs()) {
                showNotification('Please fill in all required fields.', 'error');
                return;
            }
            
            // Show loading state
            continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Validating & Proceeding...';
            continueBtn.disabled = true;
            
            // Test connection one final time before submission
            const formData = new FormData(document.getElementById('databaseForm'));
            
            // Update SQLite database field if needed
            if (formData.get('db_connection') === 'sqlite') {
                formData.set('db_database', document.getElementById('db_database_sqlite').value);
            }
            
            fetch('{{ route("installer.test.database") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Connection successful, submit the form
                    document.getElementById('databaseForm').submit();
                } else {
                    // Connection failed, show error
                    showConnectionStatus(data);
                    continueBtn.innerHTML = originalText;
                    continueBtn.disabled = false;
                    showNotification('Database connection failed. Please check your settings.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                continueBtn.innerHTML = originalText;
                continueBtn.disabled = false;
                showNotification('Failed to validate database connection.', 'error');
            });
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'error' ? 'bg-red-500' : 'bg-blue-500'} text-white max-w-md`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Handle SQLite database name sync
        document.addEventListener('DOMContentLoaded', function() {
            const sqliteInput = document.getElementById('db_database_sqlite');
            const mainDbInput = document.getElementById('db_database');
            
            if (sqliteInput) {
                sqliteInput.addEventListener('input', function() {
                    if (document.getElementById('selectedDatabase').value === 'sqlite') {
                        mainDbInput.value = this.value;
                    }
                });
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to test connection
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                testConnection();
            }
            
            // Escape to clear alerts
            if (e.key === 'Escape') {
                const alert = document.getElementById('connectionAlert');
                if (!alert.classList.contains('hidden')) {
                    alert.classList.add('hidden');
                }
            }
        });

        // Real-time form validation
        document.addEventListener('input', function(e) {
            if (e.target.matches('#databaseForm input')) {
                validateFieldRealTime(e.target);
            }
        });

        function validateFieldRealTime(field) {
            const fieldGroup = field.closest('.input-group');
            const errorMessage = fieldGroup.querySelector('.text-red-600');
            
            // Remove existing error styling
            field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
            
            // Basic validation
            let isValid = true;
            let message = '';
            
            if (field.hasAttribute('required') && field.value.trim() === '') {
                isValid = false;
                message = 'This field is required.';
            } else if (field.type === 'number') {
                const num = parseInt(field.value);
                if (isNaN(num) || num < 1 || num > 65535) {
                    isValid = false;
                    message = 'Please enter a valid port number (1-65535).';
                }
            } else if (field.name === 'db_host') {
                // Basic hostname validation
                const hostname = field.value.trim();
                if (hostname && !/^[a-zA-Z0-9.-]+$/.test(hostname)) {
                    isValid = false;
                    message = 'Please enter a valid hostname or IP address.';
                }
            }
            
            if (!isValid) {
                field.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
                field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                
                if (!errorMessage) {
                    const error = document.createElement('p');
                    error.className = 'text-sm text-red-600 mt-1';
                    error.textContent = message;
                    fieldGroup.appendChild(error);
                }
            } else if (errorMessage) {
                errorMessage.remove();
            }
        }

        // Auto-save form data to localStorage for recovery
        function saveFormData() {
            const formData = new FormData(document.getElementById('databaseForm'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key !== 'db_password') { // Don't save password
                    data[key] = value;
                }
            }
            localStorage.setItem('diveforge_db_config', JSON.stringify(data));
        }

        function loadFormData() {
            try {
                const saved = localStorage.getItem('diveforge_db_config');
                if (saved) {
                    const data = JSON.parse(saved);
                    Object.keys(data).forEach(key => {
                        const field = document.querySelector(`[name="${key}"]`);
                        if (field && field.type !== 'password') {
                            field.value = data[key];
                        }
                    });
                }
            } catch (e) {
                console.log('Could not load saved form data');
            }
        }

        // Save form data when inputs change
        document.addEventListener('input', function(e) {
            if (e.target.matches('#databaseForm input')) {
                saveFormData();
            }
        });

        // Load saved data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadFormData();
        });

        // Clear saved data on successful submission
        document.getElementById('databaseForm').addEventListener('submit', function() {
            localStorage.removeItem('diveforge_db_config');
        });

        // Accessibility improvements
        document.addEventListener('DOMContentLoaded', function() {
            // Add ARIA labels
            const requiredFields = document.querySelectorAll('input[required]');
            requiredFields.forEach(field => {
                field.setAttribute('aria-required', 'true');
            });
            
            // Add form validation feedback
            const form = document.getElementById('databaseForm');
            form.setAttribute('novalidate', 'true');
            
            // Improve screen reader support
            const statusDiv = document.getElementById('connectionStatus');
            statusDiv.setAttribute('role', 'status');
            statusDiv.setAttribute('aria-live', 'polite');
        });
    </script>
</body>
</html>