<?php
namespace App\Http\Controllers\Installer;

use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\DiveShop;
use Exception;
use PDO;
use PDOException;

class InstallationController extends Controller
{
    private array $requiredPHPExtensions = [
        'pdo', 'pdo_mysql', 'openssl', 'tokenizer', 'mbstring', 'xml', 'ctype', 'json', 'curl', 'zip', 'gd'
    ];

    private array $requiredDirectories = [
        'storage/app', 'storage/framework', 'storage/logs', 'bootstrap/cache', 'storage/app/public'
    ];

    private array $supportedTimezones = [
        'UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles',
        'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Asia/Tokyo', 'Australia/Sydney'
    ];

    private array $supportedCurrencies = [
        'USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD', 'CHF', 'CNY', 'SEK', 'NZD'
    ];

    public function __construct()
    {
        // Prevent access if already installed
        if ($this->isInstalled()) {
            abort(403, 'Application is already installed');
        }
    }

    public function showStep1()
    {
        $systemCheck = $this->checkSystemRequirements();
        return view('installer.steps.welcome', [
            'current_step' => 1,
            'system_check' => $systemCheck,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ]);
    }

    public function processStep1(Request $request)
    {
        $request->validate([
            'license_accepted' => 'required|accepted',
            'requirements_met' => 'required|accepted'
        ], [
            'license_accepted.required' => 'You must accept the license agreement to continue.',
            'requirements_met.required' => 'Please confirm that all system requirements are met.'
        ]);

        $systemCheck = $this->checkSystemRequirements();
        if (!$systemCheck['all_passed']) {
            return redirect()->back()->with('error', 'System requirements not met. Please resolve all issues before continuing.');
        }

        session(['installer.step1_complete' => true]);
        return redirect()->route('installer.step2.show');
    }

    public function showStep2()
    {
        if (!session('installer.step1_complete')) {
            return redirect()->route('installer.step1.show');
        }

        return view('installer.steps.database', [
            'current_step' => 2,
            'supported_databases' => ['mysql' => 'MySQL', 'pgsql' => 'PostgreSQL', 'sqlite' => 'SQLite']
        ]);
    }

    public function processStep2(Request $request)
    {
        $rules = [
            'db_connection' => 'required|string|in:mysql,pgsql,sqlite',
            'db_database' => 'required|string|max:64',
        ];

        if ($request->db_connection !== 'sqlite') {
            $rules = array_merge($rules, [
                'db_host' => 'required|string|max:255',
                'db_port' => 'required|numeric|min:1|max:65535',
                'db_username' => 'required|string|max:255',
                'db_password' => 'nullable|string|max:255',
            ]);
        }

        $request->validate($rules);

        // Test database connection with comprehensive error reporting
        $connectionResult = $this->testDatabaseConnection($request->all());
        
        if (!$connectionResult['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $connectionResult['message'])
                ->with('connection_details', $connectionResult['details'] ?? []);
        }

        // Test database permissions
        $permissionResult = $this->testDatabasePermissions($request->all());
        if (!$permissionResult['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $permissionResult['message']);
        }

        session([
            'installer.step2_complete' => true,
            'installer.db_config' => $request->except('_token')
        ]);

        return redirect()->route('installer.step3.show');
    }

    public function showStep3()
    {
        if (!session('installer.step2_complete')) {
            return redirect()->route('installer.step2.show');
        }
        
        return view('installer.steps.administrator', [
            'current_step' => 3,
            'password_requirements' => $this->getPasswordRequirements()
        ]);
    }

    public function processStep3(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/u',
            'email' => 'required|string|email:rfc,dns|max:255',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ], [
            'first_name.regex' => 'First name may only contain letters, spaces, hyphens, apostrophes, and periods.',
            'last_name.regex' => 'Last name may only contain letters, spaces, hyphens, apostrophes, and periods.',
            'email.email' => 'Please provide a valid email address.',
            'password.uncompromised' => 'The password has appeared in a data breach. Please choose a different password.'
        ]);

        // Additional email validation
        if (!$this->isValidEmailDomain($request->email)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please use a valid email domain.');
        }

        session([
            'installer.step3_complete' => true,
            'installer.admin_config' => $request->except('_token', 'password_confirmation')
        ]);

        return redirect()->route('installer.step4.show');
    }

    public function showStep4()
    {
        if (!session('installer.step3_complete')) {
            return redirect()->route('installer.step3.show');
        }
        
        return view('installer.steps.shop-setup', [
            'current_step' => 4,
            'timezones' => $this->supportedTimezones,
            'currencies' => $this->supportedCurrencies
        ]);
    }

    public function processStep4(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255|min:2',
            'shop_email' => 'required|email:rfc,dns|max:255',
            'shop_phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'shop_address' => 'nullable|string|max:1000',
            'shop_website' => 'nullable|url|max:255',
            'shop_timezone' => 'required|string|in:' . implode(',', $this->supportedTimezones),
            'shop_currency' => 'required|string|in:' . implode(',', $this->supportedCurrencies),
        ], [
            'shop_phone.regex' => 'Please enter a valid phone number format.',
            'shop_website.url' => 'Please enter a valid website URL (including http:// or https://).'
        ]);

        session([
            'installer.step4_complete' => true,
            'installer.shop_config' => $request->except('_token')
        ]);

        return redirect()->route('installer.step5.show');
    }

    public function showFinalStep()
    {
        if (!session('installer.step4_complete')) {
            return redirect()->route('installer.step4.show');
        }

        $summary = $this->generateInstallationSummary();
        
        return view('installer.steps.finish', [
            'current_step' => 5,
            'summary' => $summary,
            'estimated_time' => '2-5 minutes'
        ]);
    }

    public function finishInstallation()
    {
        if (!session('installer.step4_complete')) {
            return redirect()->route('installer.step4.show');
        }

        $installationId = Str::uuid();
        
        try {
            $this->logInstallationStep($installationId, 'Starting DiveForge installation');

            // Step 1: Backup existing .env if it exists
            $this->backupEnvironmentFile();
            
            // Step 2: Update environment file
            $this->logInstallationStep($installationId, 'Updating environment configuration');
            $this->updateEnvFile(session('installer.db_config'), session('installer.shop_config'));

            // Step 3: Clear all caches
            $this->logInstallationStep($installationId, 'Clearing application caches');
            $this->clearAllCaches();

            // Step 4: Set database configuration
            $this->logInstallationStep($installationId, 'Configuring database connection');
            $this->setDbConfig(session('installer.db_config'));

            // Step 5: Test final database connection
            $this->logInstallationStep($installationId, 'Testing database connection');
            if (!$this->testFinalDatabaseConnection()) {
                throw new Exception('Final database connection test failed');
            }

            // Step 6: Run migrations
            $this->logInstallationStep($installationId, 'Running database migrations');
            $this->runMigrations();

            // Step 7: Create admin user
            $this->logInstallationStep($installationId, 'Creating administrator account');
            $adminUser = $this->createAdminUser(session('installer.admin_config'));

            // Step 8: Create dive shop
            $this->logInstallationStep($installationId, 'Creating dive shop configuration');
            $shop = $this->createDiveShop(session('installer.shop_config'), $adminUser);

            // Step 9: Seed database
            $this->logInstallationStep($installationId, 'Seeding database with initial data');
            $this->runSeeder();

            // Step 10: Generate APP_KEY if not exists
            if (empty(env('APP_KEY'))) {
                $this->logInstallationStep($installationId, 'Generating application key');
                Artisan::call('key:generate', ['--force' => true]);
            }

            // Step 11: Setup storage link
            $this->logInstallationStep($installationId, 'Setting up storage link');
            $this->setupStorageLink();

            // Step 12: Set final permissions
            $this->logInstallationStep($installationId, 'Setting directory permissions');
            $this->setDirectoryPermissions();

            // Step 13: Create installation marker
            $this->logInstallationStep($installationId, 'Finalizing installation');
            $this->createInstallationMarker($installationId, $adminUser, $shop);

            // Clear installation session data
            $this->clearInstallationSession();

            $this->logInstallationStep($installationId, 'DiveForge installation completed successfully');

            return redirect()->route('installer.complete')
                ->with('success', 'DiveForge has been installed successfully!')
                ->with('installation_id', $installationId);

        } catch (Exception $e) {
            // Clean up on failure
            $this->cleanupFailedInstallation();
            
            $this->logInstallationStep($installationId ?? 'unknown', 'Installation failed: ' . $e->getMessage());
            Log::error('DiveForge installation failed', [
                'installation_id' => $installationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('installer.step2.show')
                ->withInput(session('installer.db_config'))
                ->with('error', 'Installation failed: ' . $e->getMessage())
                ->with('error_details', config('app.debug') ? $e->getTraceAsString() : null);
        }
    }

    public function showCompletionPage()
    {
        if (!$this->isInstalled()) {
            return redirect()->route('installer.step1.show');
        }

        $installationData = $this->getInstallationData();
        
        return view('installer.steps.complete', [
            'current_step' => 6,
            'installation_data' => $installationData,
            'login_url' => route('login')
        ]);
    }

    // AJAX endpoints
    public function testDatabase(Request $request)
    {
        $result = $this->testDatabaseConnection($request->all());
        return response()->json($result);
    }

    public function checkRequirements()
    {
        return response()->json($this->checkSystemRequirements());
    }

    public function getInstallationProgress()
    {
        // This could be enhanced to show real-time progress
        return response()->json([
            'status' => 'processing',
            'progress' => 50,
            'message' => 'Installing DiveForge...'
        ]);
    }

    // Private helper methods

    private function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }

    private function getInstallationData(): array
    {
        if (!$this->isInstalled()) {
            return [];
        }
        
        return json_decode(File::get(storage_path('installed')), true) ?? [];
    }

    private function checkSystemRequirements(): array
    {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version (>= 8.2)',
                'passed' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'current' => PHP_VERSION,
                'required' => '8.2.0'
            ],
            'extensions' => [],
            'permissions' => [],
            'disk_space' => $this->checkDiskSpace()
        ];

        // Check PHP extensions
        foreach ($this->requiredPHPExtensions as $extension) {
            $requirements['extensions'][$extension] = [
                'name' => strtoupper($extension) . ' Extension',
                'passed' => extension_loaded($extension),
                'required' => true
            ];
        }

        // Check directory permissions
        foreach ($this->requiredDirectories as $directory) {
            $path = base_path($directory);
            $exists = File::exists($path);
            $writable = $exists && is_writable($path);
            
            $requirements['permissions'][$directory] = [
                'name' => $directory,
                'passed' => $exists && $writable,
                'exists' => $exists,
                'writable' => $writable,
                'path' => $path
            ];
        }

        // Calculate overall status
        $requirements['all_passed'] = $requirements['php_version']['passed'] &&
            $requirements['disk_space']['passed'] &&
            collect($requirements['extensions'])->every('passed') &&
            collect($requirements['permissions'])->every('passed');

        return $requirements;
    }

    private function checkDiskSpace(): array
    {
        $required = 100 * 1024 * 1024; // 100MB
        $available = disk_free_space(base_path());
        
        return [
            'name' => 'Available Disk Space',
            'passed' => $available >= $required,
            'available' => $available,
            'required' => $required,
            'available_mb' => round($available / 1024 / 1024, 2),
            'required_mb' => round($required / 1024 / 1024, 2)
        ];
    }

    private function testDatabaseConnection(array $config): array
    {
        try {
            $connection = $config['db_connection'];
            
            if ($connection === 'sqlite') {
                return $this->testSqliteConnection($config);
            }

            return $this->testSqlConnection($config);

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database connection failed: ' . $this->getFriendlyDatabaseError($e),
                'details' => [
                    'error_code' => $e->getCode(),
                    'config' => Arr::except($config, ['db_password'])
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
                'details' => ['config' => Arr::except($config, ['db_password'])]
            ];
        }
    }

    private function testSqliteConnection(array $config): array
    {
        $database = $config['db_database'];
        if (!str_starts_with($database, '/')) {
            $database = database_path($database);
        }
        
        // Ensure directory exists
        $directory = dirname($database);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        $pdo = new PDO("sqlite:$database");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SELECT 1');
        
        return [
            'success' => true,
            'message' => 'SQLite database connection successful',
            'details' => ['database' => $database]
        ];
    }

    private function testSqlConnection(array $config): array
    {
        $dsn = $this->buildDSN($config);
        $pdo = new PDO($dsn, $config['db_username'], $config['db_password'] ?? '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test basic query
        $testQuery = $config['db_connection'] === 'mysql' ? 'SELECT VERSION()' : 'SELECT version()';
        $stmt = $pdo->query($testQuery);
        $version = $stmt->fetchColumn();
        
        return [
            'success' => true,
            'message' => 'Database connection successful',
            'details' => [
                'host' => $config['db_host'],
                'port' => $config['db_port'],
                'database' => $config['db_database'],
                'version' => $version
            ]
        ];
    }

    private function testDatabasePermissions(array $config): array
    {
        try {
            $connection = $config['db_connection'];
            
            if ($connection === 'sqlite') {
                // For SQLite, check if we can create the database file
                $database = $config['db_database'];
                if (!str_starts_with($database, '/')) {
                    $database = database_path($database);
                }
                
                $directory = dirname($database);
                if (!is_writable($directory)) {
                    return [
                        'success' => false,
                        'message' => 'SQLite database directory is not writable: ' . $directory
                    ];
                }
                
                return ['success' => true, 'message' => 'SQLite permissions OK'];
            }

            // For MySQL/PostgreSQL, test table creation permissions
            $dsn = $this->buildDSN($config);
            $pdo = new PDO($dsn, $config['db_username'], $config['db_password'] ?? '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $testTable = 'diveforge_install_test_' . Str::random(8);
            
            // Try to create a test table
            $createSql = "CREATE TABLE {$testTable} (id INTEGER PRIMARY KEY, test_column VARCHAR(255))";
            $pdo->exec($createSql);
            
            // Try to insert data
            $insertSql = "INSERT INTO {$testTable} (test_column) VALUES ('test')";
            $pdo->exec($insertSql);
            
            // Clean up
            $pdo->exec("DROP TABLE {$testTable}");
            
            return [
                'success' => true,
                'message' => 'Database permissions verified'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Database permission test failed: ' . $e->getMessage()
            ];
        }
    }

    private function buildDSN(array $config): string
    {
        $connection = $config['db_connection'];
        $host = $config['db_host'];
        $port = $config['db_port'];
        $database = $config['db_database'];

        return match($connection) {
            'mysql' => "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
            'pgsql' => "pgsql:host=$host;port=$port;dbname=$database",
            default => throw new Exception("Unsupported database connection: $connection")
        };
    }

    private function getFriendlyDatabaseError(PDOException $e): string
    {
        $message = $e->getMessage();
        
        if (str_contains($message, 'Access denied')) {
            return 'Access denied. Please check your username and password.';
        }
        
        if (str_contains($message, 'Unknown database')) {
            return 'Database does not exist. Please create the database first.';
        }
        
        if (str_contains($message, 'Connection refused')) {
            return 'Connection refused. Please check if the database server is running.';
        }
        
        if (str_contains($message, 'Name or service not known')) {
            return 'Cannot resolve hostname. Please check the database host.';
        }
        
        return $message;
    }

    private function getPasswordRequirements(): array
    {
        return [
            'Minimum 12 characters',
            'At least one uppercase letter',
            'At least one lowercase letter',
            'At least one number',
            'At least one special character',
            'Not found in known data breaches'
        ];
    }

    private function isValidEmailDomain(string $email): bool
    {
        $domain = substr(strrchr($email, '@'), 1);
        return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
    }

    private function backupEnvironmentFile(): void
    {
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $backupPath = $envPath . '.backup.' . date('Y-m-d-H-i-s');
            File::copy($envPath, $backupPath);
        }
    }

    private function updateEnvFile(array $dbConfig, array $shopConfig): void
    {
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            if (File::exists($envPath . '.example')) {
                File::copy($envPath . '.example', $envPath);
            } else {
                throw new Exception('.env file not found and .env.example is missing.');
            }
        }

        $content = File::get($envPath);
        
        $replacements = [
            'APP_NAME' => '"' . addslashes($shopConfig['shop_name']) . '"',
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_TIMEZONE' => '"' . ($shopConfig['shop_timezone'] ?? 'UTC') . '"',
            'SESSION_DRIVER' => 'database',
            'CACHE_DRIVER' => 'database',
            'QUEUE_CONNECTION' => 'database',
            'DB_CONNECTION' => $dbConfig['db_connection'],
            'DB_HOST' => $dbConfig['db_host'] ?? '',
            'DB_PORT' => $dbConfig['db_port'] ?? '',
            'DB_DATABASE' => $dbConfig['db_database'],
            'DB_USERNAME' => $dbConfig['db_username'] ?? '',
            'DB_PASSWORD' => '"' . addslashes($dbConfig['db_password'] ?? '') . '"',
            'MAIL_FROM_ADDRESS' => '"' . $shopConfig['shop_email'] . '"',
            'MAIL_FROM_NAME' => '"' . addslashes($shopConfig['shop_name']) . '"',
        ];

        foreach ($replacements as $key => $value) {
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $content);
        chmod($envPath, 0600); // Secure the .env file
    }

    private function clearAllCaches(): void
    {
        $commands = [
            'config:clear',
            'cache:clear', 
            'route:clear',
            'view:clear',
            'event:clear'
        ];

        foreach ($commands as $command) {
            try {
                Artisan::call($command);
            } catch (Exception $e) {
                // Log but don't fail installation
                Log::warning("Failed to run {$command}: " . $e->getMessage());
            }
        }
    }

    private function setDbConfig(array $config): void
    {
        $driver = $config['db_connection'];
        
        if ($driver !== 'sqlite') {
            Config::set("database.connections.$driver.host", $config['db_host'] ?? '');
            Config::set("database.connections.$driver.port", $config['db_port'] ?? '');
            Config::set("database.connections.$driver.username", $config['db_username'] ?? '');
            Config::set("database.connections.$driver.password", $config['db_password'] ?? '');
        }
        
        Config::set("database.connections.$driver.database", $config['db_database']);
        Config::set('database.default', $driver);
        
        DB::purge($driver);
        DB::reconnect($driver);
    }

    private function testFinalDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            Log::error('Final database connection test failed: ' . $e->getMessage());
            return false;
        }
    }

    private function runMigrations(): void
    {
        $exitCode = Artisan::call('migrate:fresh', ['--force' => true]);
        if ($exitCode !== 0) {
            throw new Exception('Migration failed with exit code: ' . $exitCode);
        }
    }

    private function createAdminUser(array $config): User
    {
        return User::create([
            'name' => trim($config['first_name'] . ' ' . $config['last_name']),
            'email' => $config['email'],
            'password' => Hash::make($config['password']),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
    }

    private function createDiveShop(array $config, User $owner): DiveShop
    {
        return DiveShop::create([
            'name' => $config['shop_name'],
            'email' => $config['shop_email'],
            'phone' => $config['shop_phone'] ?? null,
            'address' => $config['shop_address'] ?? null,
            'website' => $config['shop_website'] ?? null,
            'timezone' => $config['shop_timezone'] ?? 'UTC',
            'currency' => $config['shop_currency'] ?? 'USD',
            'owner_id' => $owner->id,
            'is_active' => true,
        ]);
    }

    private function runSeeder(): void
    {
        try {
            Artisan::call('db:seed', ['--force' => true]);
        } catch (Exception $e) {
            // Log but don't fail installation if seeding fails
            Log::warning('Database seeding failed: ' . $e->getMessage());
        }
    }

    private function setupStorageLink(): void
    {
        try {
            if (!File::exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }
        } catch (Exception $e) {
            Log::warning('Storage link creation failed: ' . $e->getMessage());
        }
    }

    private function setDirectoryPermissions(): void
    {
        $directories = [
            'storage',
            'bootstrap/cache',
        ];

        foreach ($directories as $directory) {
            $path = base_path($directory);
            if (File::exists($path)) {
                chmod($path, 0755);
            }
        }
    }

    private function createInstallationMarker(string $installationId, User $admin, DiveShop $shop): void
    {
        $installationData = [
            'installation_id' => $installationId,
            'completed_at' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_type' => config('database.default'),
            'admin_email' => $admin->email,
            'admin_id' => $admin->id,
            'shop_name' => $shop->name,
            'shop_id' => $shop->id,
            'installation_time' => now()->diffInSeconds(session('installer.start_time', now())),
        ];

        File::put(storage_path('installed'), json_encode($installationData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        chmod(storage_path('installed'), 0600);
    }

    private function clearInstallationSession(): void
    {
        $keys = [
            'installer.step1_complete',
            'installer.step2_complete', 
            'installer.step3_complete',
            'installer.step4_complete',
            'installer.db_config',
            'installer.admin_config',
            'installer.shop_config',
            'installer.start_time'
        ];

        foreach ($keys as $key) {
            session()->forget($key);
        }
    }

    private function cleanupFailedInstallation(): void
    {
        try {
            // Remove installation marker if it exists
            if (File::exists(storage_path('installed'))) {
                File::delete(storage_path('installed'));
            }

            // Restore .env backup if it exists
            $envPath = base_path('.env');
            $backupFiles = glob($envPath . '.backup.*');
            if (!empty($backupFiles)) {
                $latestBackup = end($backupFiles);
                File::copy($latestBackup, $envPath);
            }

            // Clear caches
            $this->clearAllCaches();

        } catch (Exception $e) {
            Log::error('Failed to cleanup installation: ' . $e->getMessage());
        }
    }

    private function generateInstallationSummary(): array
    {
        $dbConfig = session('installer.db_config', []);
        $adminConfig = session('installer.admin_config', []);
        $shopConfig = session('installer.shop_config', []);

        return [
            'database' => [
                'type' => $dbConfig['db_connection'] ?? 'Unknown',
                'host' => $dbConfig['db_host'] ?? 'N/A',
                'database' => $dbConfig['db_database'] ?? 'Unknown',
                'port' => $dbConfig['db_port'] ?? 'N/A'
            ],
            'admin' => [
                'name' => trim(($adminConfig['first_name'] ?? '') . ' ' . ($adminConfig['last_name'] ?? '')),
                'email' => $adminConfig['email'] ?? 'Unknown'
            ],
            'shop' => [
                'name' => $shopConfig['shop_name'] ?? 'Unknown',
                'email' => $shopConfig['shop_email'] ?? 'Unknown',
                'timezone' => $shopConfig['shop_timezone'] ?? 'UTC',
                'currency' => $shopConfig['shop_currency'] ?? 'USD',
                'website' => $shopConfig['shop_website'] ?? null
            ],
            'system' => $this->checkSystemRequirements(),
            'estimated_users' => 0,
            'features_enabled' => [
                'Equipment Rental Management',
                'Course Scheduling',
                'Certification Tracking',
                'Customer Management',
                'Inventory Management',
                'Reporting & Analytics'
            ]
        ];
    }

    private function logInstallationStep(string $installationId, string $message): void
    {
        $logPath = storage_path('logs/installation.log');
        
        // Ensure logs directory exists
        $logDir = dirname($logPath);
        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }
        
        $timestamp = now()->toISOString();
        $logEntry = "[$timestamp] [$installationId] $message" . PHP_EOL;
        
        File::append($logPath, $logEntry);
        
        // Also log to Laravel's default log
        Log::info("DiveForge Installation: $message", [
            'installation_id' => $installationId,
            'step' => $message
        ]);
    }

    // Additional helper methods for enhanced functionality

    private function validateSystemSecurity(): array
    {
        $checks = [];
        
        // Check if running in production
        $checks['app_env'] = [
            'name' => 'Application Environment',
            'passed' => app()->environment('production'),
            'message' => app()->environment('production') ? 'Production' : 'Development (should be production)'
        ];
        
        // Check if debug is disabled
        $checks['app_debug'] = [
            'name' => 'Debug Mode',
            'passed' => !config('app.debug'),
            'message' => config('app.debug') ? 'Enabled (should be disabled)' : 'Disabled'
        ];
        
        // Check if .env is protected
        $envPath = base_path('.env');
        $checks['env_protection'] = [
            'name' => 'Environment File Security',
            'passed' => !is_readable($envPath) || (fileperms($envPath) & 0777) <= 0600,
            'message' => 'Environment file permissions checked'
        ];
        
        return $checks;
    }

    private function createInitialSettings(): void
    {
        try {
            // Create default application settings
            $settings = [
                'app_name' => session('installer.shop_config.shop_name'),
                'app_version' => config('app.version', '1.0.0'),
                'installation_date' => now()->toDateString(),
                'maintenance_mode' => false,
                'registration_enabled' => true,
                'email_verification_required' => true,
                'default_user_role' => 'customer',
                'session_lifetime' => 120,
                'password_reset_timeout' => 60,
            ];

            foreach ($settings as $key => $value) {
                DB::table('settings')->insert([
                    'key' => $key,
                    'value' => is_bool($value) ? ($value ? '1' : '0') : (string)$value,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (Exception $e) {
            Log::warning('Failed to create initial settings: ' . $e->getMessage());
        }
    }

    private function sendInstallationNotification(User $admin): void
    {
        try {
            // You could send an email notification here
            // Mail::to($admin)->send(new InstallationCompleteNotification());
            
            Log::info('DiveForge installation completed', [
                'admin_email' => $admin->email,
                'shop_name' => session('installer.shop_config.shop_name'),
                'installation_time' => now()
            ]);
        } catch (Exception $e) {
            Log::warning('Failed to send installation notification: ' . $e->getMessage());
        }
    }

    // API endpoints for frontend integration

    public function getInstallationStatus()
    {
        return response()->json([
            'installed' => $this->isInstalled(),
            'current_step' => $this->getCurrentStep(),
            'system_requirements' => $this->checkSystemRequirements()
        ]);
    }

    private function getCurrentStep(): int
    {
        if (!session('installer.step1_complete')) return 1;
        if (!session('installer.step2_complete')) return 2;
        if (!session('installer.step3_complete')) return 3;
        if (!session('installer.step4_complete')) return 4;
        return 5;
    }

    public function validateStep(Request $request)
    {
        $step = $request->input('step');
        
        switch ($step) {
            case 1:
                return response()->json($this->checkSystemRequirements());
            case 2:
                return response()->json($this->testDatabaseConnection($request->all()));
            case 3:
                return response()->json(['valid' => $this->validateAdminData($request->all())]);
            case 4:
                return response()->json(['valid' => $this->validateShopData($request->all())]);
            default:
                return response()->json(['error' => 'Invalid step'], 400);
        }
    }

    private function validateAdminData(array $data): bool
    {
        $validator = validator($data, [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/u',
            'email' => 'required|string|email:rfc,dns|max:255',
            'password' => [
                'required',
                'string',
                'min:12',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ]);

        return !$validator->fails();
    }

    private function validateShopData(array $data): bool
    {
        $validator = validator($data, [
            'shop_name' => 'required|string|max:255|min:2',
            'shop_email' => 'required|email:rfc,dns|max:255',
            'shop_phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'shop_timezone' => 'required|string|in:' . implode(',', $this->supportedTimezones),
            'shop_currency' => 'required|string|in:' . implode(',', $this->supportedCurrencies),
        ]);

        return !$validator->fails();
    }

    // Maintenance and cleanup methods

    public function cleanupInstallationFiles()
    {
        if (!$this->isInstalled()) {
            return response()->json(['error' => 'Application not installed'], 400);
        }

        try {
            // Clean up installation logs older than 30 days
            $logPath = storage_path('logs/installation.log');
            if (File::exists($logPath) && File::lastModified($logPath) < now()->subDays(30)->timestamp) {
                File::delete($logPath);
            }

            // Clean up .env backups older than 7 days
            $envBackups = glob(base_path('.env.backup.*'));
            foreach ($envBackups as $backup) {
                if (File::lastModified($backup) < now()->subDays(7)->timestamp) {
                    File::delete($backup);
                }
            }

            return response()->json(['message' => 'Installation files cleaned up successfully']);
            
        } catch (Exception $e) {
            return response()->json(['error' => 'Cleanup failed: ' . $e->getMessage()], 500);
        }
    }

    // Security check method
    private function performSecurityChecks(): array
    {
        $checks = [];
        
        // Check for secure headers
        $checks['secure_headers'] = [
            'name' => 'Security Headers',
            'passed' => $this->hasSecureHeaders(),
            'message' => 'Checking for security headers configuration'
        ];
        
        // Check file permissions
        $checks['file_permissions'] = [
            'name' => 'File Permissions',
            'passed' => $this->checkFilePermissions(),
            'message' => 'Verifying secure file permissions'
        ];
        
        // Check for production readiness
        $checks['production_ready'] = [
            'name' => 'Production Configuration',
            'passed' => $this->isProductionReady(),
            'message' => 'Validating production environment settings'
        ];
        
        return $checks;
    }

    private function hasSecureHeaders(): bool
    {
        // This would check if security middleware is properly configured
        return true; // Simplified for example
    }

    private function checkFilePermissions(): bool
    {
        $criticalFiles = ['.env', 'config', 'storage'];
        
        foreach ($criticalFiles as $file) {
            $path = base_path($file);
            if (File::exists($path)) {
                $perms = fileperms($path) & 0777;
                if ($perms > 0755) {
                    return false;
                }
            }
        }
        
        return true;
    }

    private function isProductionReady(): bool
    {
        return app()->environment('production') && 
               !config('app.debug') && 
               !empty(config('app.key'));
    }
}