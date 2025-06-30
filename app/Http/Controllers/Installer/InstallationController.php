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

    // Check if the application is already installed
    private function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
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

        session(['installer.step1_complete' => true, 'installer.start_time' => now()]);
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

        // Test database connection
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
            'email' => 'required|email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'first_name.regex' => 'First name may only contain letters, spaces, hyphens, apostrophes, and periods.',
            'last_name.regex' => 'Last name may only contain letters, spaces, hyphens, apostrophes, and periods.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

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
            'shop_name' => 'required|string|max:255',
            'shop_email' => 'required|email|max:255',
            'shop_phone' => 'nullable|string|max:20',
            'shop_address' => 'nullable|string|max:500',
            'shop_website' => 'nullable|url|max:255',
            'shop_timezone' => 'required|string|in:' . implode(',', $this->supportedTimezones),
            'shop_currency' => 'required|string|in:' . implode(',', $this->supportedCurrencies),
        ]);

        session([
            'installer.step4_complete' => true,
            'installer.shop_config' => $request->except('_token')
        ]);

        return redirect()->route('installer.finish.show');
    }

    public function showFinish()
    {
        if (!session('installer.step4_complete')) {
            return redirect()->route('installer.step4.show');
        }

        $summary = $this->generateInstallationSummary();
        
        return view('installer.steps.finish', [
            'current_step' => 5,
            'summary' => $summary
        ]);
    }

    public function processFinish(Request $request)
    {
        $installationId = Str::uuid()->toString();
        
        try {
            // Step 1: Backup current .env file
            $this->logInstallationStep($installationId, 'Starting DiveForge installation');
            $this->backupEnvFile();

            // Step 2: Update .env file with new configuration
            $this->logInstallationStep($installationId, 'Updating environment configuration');
            $this->updateEnvFile([
                'db_config' => session('installer.db_config'),
                'shop_config' => session('installer.shop_config')
            ]);

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
            
            return redirect()->back()
                ->with('error', 'Installation failed: ' . $e->getMessage())
                ->with('installation_error', true);
        }
    }

    public function showComplete()
    {
        $installationId = session('installation_id');
        $summary = $this->generateInstallationSummary();
        
        return view('installer.steps.complete', [
            'installation_id' => $installationId,
            'summary' => $summary
        ]);
    }

    // AJAX endpoint for testing database connection
    public function testConnection(Request $request)
    {
        $result = $this->testDatabaseConnection($request->all());
        return response()->json($result);
    }

    // Private helper methods
    private function checkSystemRequirements(): array
    {
        $requirements = [
            'php_version' => [
                'required' => '8.1.0',
                'current' => PHP_VERSION,
                'passed' => version_compare(PHP_VERSION, '8.1.0', '>=')
            ],
            'extensions' => [],
            'directories' => [],
            'permissions' => []
        ];

        // Check PHP extensions
        foreach ($this->requiredPHPExtensions as $extension) {
            $requirements['extensions'][$extension] = [
                'required' => true,
                'current' => extension_loaded($extension),
                'passed' => extension_loaded($extension)
            ];
        }

        // Check directories and permissions
        foreach ($this->requiredDirectories as $directory) {
            $path = base_path($directory);
            $exists = File::exists($path);
            $writable = $exists ? File::isWritable($path) : false;
            
            $requirements['directories'][$directory] = [
                'exists' => $exists,
                'writable' => $writable,
                'passed' => $exists && $writable
            ];
        }

        // Check .env file
        $envPath = base_path('.env');
        $requirements['permissions']['env_file'] = [
            'exists' => File::exists($envPath),
            'writable' => File::exists($envPath) ? File::isWritable($envPath) : File::isWritable(base_path()),
            'passed' => File::exists($envPath) ? File::isWritable($envPath) : File::isWritable(base_path())
        ];

        // Determine if all requirements are met
        $requirements['all_passed'] = $requirements['php_version']['passed'] &&
            collect($requirements['extensions'])->every('passed') &&
            collect($requirements['directories'])->every('passed') &&
            collect($requirements['permissions'])->every('passed');

        return $requirements;
    }

    private function testDatabaseConnection(array $config): array
    {
        try {
            $driver = $config['db_connection'];
            
            if ($driver === 'sqlite') {
                $database = $config['db_database'];
                if (!str_starts_with($database, '/')) {
                    $database = database_path($database);
                }
                
                $dsn = "sqlite:{$database}";
                $username = null;
                $password = null;
            } else {
                $host = $config['db_host'];
                $port = $config['db_port'];
                $database = $config['db_database'];
                $username = $config['db_username'];
                $password = $config['db_password'] ?? '';
                
                $dsn = "{$driver}:host={$host};port={$port};dbname={$database}";
            }

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $pdo->exec('SELECT 1');
            
            return [
                'success' => true,
                'message' => 'Database connection successful',
                'details' => [
                    'driver' => $driver,
                    'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
                ]
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'details' => [
                    'error_code' => $e->getCode(),
                    'driver' => $driver ?? 'unknown'
                ]
            ];
        }
    }

    private function testDatabasePermissions(array $config): array
    {
        try {
            $driver = $config['db_connection'];
            
            if ($driver === 'sqlite') {
                $database = $config['db_database'];
                if (!str_starts_with($database, '/')) {
                    $database = database_path($database);
                }
                
                $directory = dirname($database);
                if (!File::isWritable($directory)) {
                    return [
                        'success' => false,
                        'message' => 'SQLite database directory is not writable: ' . $directory
                    ];
                }
            } else {
                // Test if we can create and drop a test table
                $dsn = "{$driver}:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_database']}";
                $pdo = new PDO($dsn, $config['db_username'], $config['db_password'] ?? '');
                
                $testTable = 'diveforge_install_test_' . time();
                $pdo->exec("CREATE TABLE {$testTable} (id INT PRIMARY KEY)");
                $pdo->exec("DROP TABLE {$testTable}");
            }

            return ['success' => true, 'message' => 'Database permissions are adequate'];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Insufficient database permissions: ' . $e->getMessage()
            ];
        }
    }

    private function getPasswordRequirements(): array
    {
        return [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => true
        ];
    }

    private function backupEnvFile(): void
    {
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $backupPath = $envPath . '.backup.' . time();
            File::copy($envPath, $backupPath);
        }
    }

    private function updateEnvFile(array $configs): void
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');
        
        // Use .env.example as template if .env doesn't exist
        if (!File::exists($envPath) && File::exists($envExamplePath)) {
            File::copy($envExamplePath, $envPath);
        }
        
        $content = File::exists($envPath) ? File::get($envPath) : '';
        
        $dbConfig = $configs['db_config'];
        $shopConfig = $configs['shop_config'];
        
        $replacements = [
            'APP_NAME' => '"' . addslashes($shopConfig['shop_name']) . '"',
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_TIMEZONE' => '"' . $shopConfig['shop_timezone'] . '"',
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
        chmod($envPath, 0600);
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
            if (File::exists(storage_path('installed'))) {
                File::delete(storage_path('installed'));
            }

            $envPath = base_path('.env');
            $backupFiles = glob($envPath . '.backup.*');
            if (!empty($backupFiles)) {
                $latestBackup = end($backupFiles);
                File::copy($latestBackup, $envPath);
            }

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
        
        $logDir = dirname($logPath);
        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }
        
        $timestamp = now()->toISOString();
        $logEntry = "[$timestamp] [$installationId] $message" . PHP_EOL;
        
        File::append($logPath, $logEntry);
    }
}