#!/bin/bash

# ==============================================================================
# DiveForge Complete Installer Setup Script (v12 - Routing Fix)
#
# This script is the definitive version and handles the entire setup process.
# This version corrects the routing logic to fix the "Page Expired" and
# incorrect URL issues.
#
# It will:
# 1. Create/overwrite all necessary installer files in your LOCAL directory.
# 2. Sync these local files up to the production server.
# 3. Execute all necessary commands remotely on the server.
#
# USAGE:
# 1. Save this file as `setup_installer_final.sh` in your local project root.
# 2. Make it executable: chmod +x setup_installer_final.sh
# 3. Run it from your local project root: ./setup_installer_final.sh
# ==============================================================================

# --- Configuration ---
PROD_PATH="/var/www/html/DiveForge/"
PROD_HOST="williamnash@lori-lan"
PROD_USER="apache"

# --- Helper Functions ---
echo_success() {
  echo -e "\033[32m✓ $1\033[0m"
}

echo_info() {
  echo -e "\033[34mℹ $1\033[0m"
}

echo_error() {
  echo -e "\033[31m✗ ERROR: $1\033[0m"
}

# --- Start Script ---
echo_info "Starting DiveForge Complete Installer Setup..."

# --- 1. Create Local Directories ---
echo_info "Creating necessary local directories..."
mkdir -p app/Http/Controllers/Installer
mkdir -p app/Http/Middleware
mkdir -p resources/views/installer/steps
echo_success "Local directories ensured."

# --- 2. Create Local Files ---
echo_info "Generating all necessary PHP and Blade files locally..."

# Middleware: InstallerNotInstalled.php
cat << 'EOF' > app/Http/Middleware/InstallerNotInstalled.php
<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class InstallerNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }
        return $next($request);
    }
}
EOF

# Middleware: VerifyCsrfToken.php (with exception for the installer)
cat << 'EOF' > app/Http/Middleware/VerifyCsrfToken.php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'install/*', // Exclude all installer routes from CSRF protection
    ];
}
EOF


# Controller: InstallationController.php
cat << 'EOF' > app/Http/Controllers/Installer/InstallationController.php
<?php
namespace App\Http\Controllers\Installer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DiveShop;
use Exception;
class InstallationController extends Controller
{
    public function showStep1() { return view('installer.steps.welcome', ['current_step' => 1]); }
    public function processStep1(Request $request) {
        $request->validate(['license_accepted' => 'required']);
        session(['installer.step1_complete' => true]);
        return redirect()->route('installer.step2.show');
    }
    public function showStep2() {
        if (!session('installer.step1_complete')) return redirect()->route('installer.step1.show');
        return view('installer.steps.database', ['current_step' => 2]);
    }
    public function processStep2(Request $request) {
        $request->validate([
            'db_connection' => 'required|string', 'db_host' => 'required|string',
            'db_port' => 'required|numeric', 'db_database' => 'required|string',
            'db_username' => 'required|string', 'db_password' => 'nullable|string',
        ]);
        $this->setDbConfig($request->all());
        try {
            DB::connection()->getPdo();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Could not connect to the database. Please check your credentials. Error: ' . $e->getMessage());
        }
        session(['installer.step2_complete' => true, 'installer.db_config' => $request->except('_token')]);
        return redirect()->route('installer.step3.show');
    }
    public function showStep3() {
        if (!session('installer.step2_complete')) return redirect()->route('installer.step2.show');
        return view('installer.steps.administrator', ['current_step' => 3]);
    }
    public function processStep3(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', 'password' => 'required|string|min:12|confirmed',
        ]);
        session(['installer.step3_complete' => true, 'installer.admin_config' => $request->except('_token', 'password_confirmation')]);
        return redirect()->route('installer.step4.show');
    }
    public function showStep4() {
        if (!session('installer.step3_complete')) return redirect()->route('installer.step3.show');
        return view('installer.steps.shop-setup', ['current_step' => 4]);
    }
    public function processStep4(Request $request) {
        $request->validate(['shop_name' => 'required', 'shop_email' => 'required|email']);
        session(['installer.step4_complete' => true, 'installer.shop_config' => $request->except('_token')]);
        return redirect()->route('installer.step5.show');
    }
    public function showFinalStep() {
        if (!session('installer.step4_complete')) return redirect()->route('installer.step4.show');
        return view('installer.steps.finish', ['current_step' => 5]);
    }
    public function finishInstallation()
    {
        if (!session('installer.step4_complete')) return redirect()->route('installer.step4.show');
        try {
            $this->updateEnvFile(session('installer.db_config'));
            Artisan::call('config:clear');
            $this->setDbConfig(session('installer.db_config'));
            Artisan::call('migrate:fresh', ['--force' => true]);
            $adminConfig = session('installer.admin_config');
            User::create(['name' => $adminConfig['first_name'] . ' ' . $adminConfig['last_name'], 'email' => $adminConfig['email'], 'password' => Hash::make($adminConfig['password']),]);
            $shopConfig = session('installer.shop_config');
            DiveShop::create(['name' => $shopConfig['shop_name'], 'email' => $shopConfig['shop_email'], 'phone' => $shopConfig['shop_phone'] ?? null, 'address' => $shopConfig['shop_address'] ?? null,]);
            Artisan::call('db:seed', ['--force' => true]);
            File::put(storage_path('installed'), 'DiveForge installation completed on ' . now());
            session()->flush();
            return redirect()->route('installer.complete');
        } catch (Exception $e) {
            File::delete(storage_path('installed'));
            return redirect()->route('installer.step2.show')->withInput(session('installer.db_config'))->with('error', 'Installation failed during the final step. Please verify your database credentials. Error: ' . $e->getMessage());
        }
    }
    public function showCompletionPage() {
        if (!File::exists(storage_path('installed'))) {
            return redirect()->route('installer.step1.show');
        }
        return view('installer.steps.complete', ['current_step' => 5]);
    }
    private function updateEnvFile(array $config) { 
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            if (File::exists($envPath . '.example')) { File::copy($envPath . '.example', $envPath); } else { throw new Exception('.env file not found.'); }
        }
        $content = File::get($envPath);
        $replacements = [
            'APP_NAME' => '"DiveForge"', 'APP_ENV' => 'production', 'APP_DEBUG' => 'false', 'SESSION_DRIVER' => 'database',
            'DB_CONNECTION' => $config['db_connection'] ?? null, 'DB_HOST' => $config['db_host'] ?? null,
            'DB_PORT' => $config['db_port'] ?? null, 'DB_DATABASE' => $config['db_database'] ?? null,
            'DB_USERNAME' => $config['db_username'] ?? null, 'DB_PASSWORD' => '"' . ($config['db_password'] ?? '') . '"',
        ];
        foreach ($replacements as $key => $value) {
            if ($value === null) continue;
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }
        File::put($envPath, $content);
    }
    private function setDbConfig(array $config) { 
        $driver = $config['db_connection'];
        Config::set("database.connections.$driver.host", $config['db_host']);
        Config::set("database.connections.$driver.port", $config['db_port']);
        Config::set("database.connections.$driver.database", $config['db_database']);
        Config::set("database.connections.$driver.username", $config['db_username']);
        Config::set("database.connections.$driver.password", $config['db_password'] ?? '');
        DB::purge($driver);
    }
}
EOF

# Routes: web.php
cat << 'EOF' > routes/web.php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Installer\InstallationController;

Route::get('/', function () {
    if (!file_exists(storage_path('installed'))) {
        return redirect()->route('installer.step1.show');
    }
    return 'DiveForge is Installed!';
})->name('home');

Route::prefix('install')
    ->middleware(['web', 'installer.not_installed'])
    ->group(function () {
    
    // Step 1: Welcome
    Route::get('/', [InstallationController::class, 'showStep1'])->name('installer.step1.show');
    Route::post('/', [InstallationController::class, 'processStep1'])->name('installer.step1.process');
    
    // Step 2: Database
    Route::get('/database', [InstallationController::class, 'showStep2'])->name('installer.step2.show');
    Route::post('/database', [InstallationController::class, 'processStep2'])->name('installer.step2.process');
    
    // Step 3: Administrator
    Route::get('/administrator', [InstallationController::class, 'showStep3'])->name('installer.step3.show');
    Route::post('/administrator', [InstallationController::class, 'processStep3'])->name('installer.step3.process');
    
    // Step 4: Shop Setup
    Route::get('/shop-setup', [InstallationController::class, 'showStep4'])->name('installer.step4.show');
    Route::post('/shop-setup', [InstallationController::class, 'processStep4'])->name('installer.step4.process');
    
    // Step 5: Finish
    Route::get('/finish', [InstallationController::class, 'showFinalStep'])->name('installer.step5.show');
    Route::post('/finish', [InstallationController::class, 'finishInstallation'])->name('installer.finish');
});

Route::get('/install/complete', [InstallationController::class, 'showCompletionPage'])->name('installer.complete');
EOF

# Config: bootstrap/app.php
cat << 'EOF' > bootstrap/app.php
<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'installer.not_installed' => \App\Http\Middleware\InstallerNotInstalled::class,
        ]);
        
        // This is important for newer Laravel versions
        $middleware->appendToGroup('web', \App\Http\Middleware\VerifyCsrfToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
EOF

# Views: layout.blade.php and all steps
# ... (All `cat << EOF > resources/views/...` commands are here) ...
# Layout
cat << 'EOF' > resources/views/installer/layout.blade.php
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiveForge Installation Wizard</title>
    <link rel="icon" href="https://placehold.co/32x32/0d6efd/FFFFFF?text=DF">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .btn { @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors; }
        .btn-primary { @apply text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500; }
        .btn-secondary { @apply text-slate-700 bg-slate-100 hover:bg-slate-200 focus:ring-slate-500; }
        .form-input { @apply block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm; }
    </style>
</head>
<body class="h-full">
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full space-y-8 bg-white p-2 sm:p-8 rounded-2xl shadow-2xl">
        <header class="text-center">
            <img src="https://placehold.co/200x50/111827/FFFFFF?text=DiveForge" alt="DiveForge" class="mx-auto h-12 w-auto">
            <h1 class="mt-6 text-3xl font-extrabold text-gray-900">DiveForge Installation</h1>
        </header>

        <nav class="p-4 bg-slate-50 rounded-lg">
            <ol class="flex items-center w-full">
                @php $steps = ['Welcome', 'Database', 'Admin', 'Shop', 'Finish']; @endphp
                @foreach($steps as $i => $title)
                    @php $index = $i + 1; @endphp
                    <li class="flex w-full items-center {{ !$loop->last ? 'text-blue-600 after:content-[\'\'] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-2 after:inline-block' : '' }}">
                        @if(session('installer.step'.($index-1).'_complete') || $current_step >= $index)
                            <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                @if(session('installer.step'.$index.'_complete') || ($current_step > $index))
                                    <svg class="w-5 h-5 text-blue-600 lg:w-6 lg:h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                @else
                                    <span class="font-bold text-blue-600">{{ $index }}</span>
                                @endif
                            </span>
                        @else
                             <span class="flex items-center justify-center w-10 h-10 bg-slate-100 rounded-full lg:h-12 lg:w-12 text-slate-500 shrink-0">{{ $index }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>

        <main>
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Error: {{ session('error') }}</h3></div></div></div>
            @endif
            @if($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3><div class="mt-2 text-sm text-red-700"><ul role="list" class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div></div></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
EOF
# Welcome View
cat << 'EOF' > resources/views/installer/steps/welcome.blade.php
@extends('installer.layout', ['current_step' => 1])
@section('content')
<form action="{{ route('installer.step1.process') }}" method="POST">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Welcome to DiveForge</h2><p class="mt-2 text-sm text-slate-600">The Universal Open Source Dive Shop Management Platform.</p></div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🌊</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Universal Agency Support</p><p class="text-sm text-slate-500">PADI, SSI, TDI, NAUI, and more.</p></div></div>
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🔓</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Open Source Freedom</p><p class="text-sm text-slate-500">GPL v3 licensed for community ownership.</p></div></div>
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🏢</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Enterprise Ready</p><p class="text-sm text-slate-500">PCI DSS compliant with robust security.</p></div></div>
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🔄</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Easy Migration</p><p class="text-sm text-slate-500">Transition from existing systems.</p></div></div>
    </div>
    <div class="mt-8 p-6 bg-slate-50/50 border border-slate-200 rounded-lg">
        <h3 class="text-base font-semibold leading-6 text-slate-900">License Agreement</h3>
        <p class="mt-2 text-sm text-slate-600">DiveForge is licensed under the GNU General Public License v3.0 (GPL v3). By proceeding, you agree to its terms.</p>
        <fieldset class="mt-4"><div class="space-y-4"><div class="flex items-start"><div class="flex h-6 items-center"><input id="license-accepted" name="license_accepted" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"></div><div class="ml-3 text-sm leading-6"><label for="license-accepted" class="font-medium text-slate-900">I accept the GPL v3 license terms.</label></div></div></div></fieldset>
    </div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-end"><button type="submit" class="btn btn-primary">Next Step<svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" /></svg></button></div></div>
</form>
@endsection
EOF
# Database View
cat << 'EOF' > resources/views/installer/steps/database.blade.php
@extends('installer.layout', ['current_step' => 2])
@section('content')
<form action="{{ route('installer.step2.process') }}" method="POST" class="space-y-8">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Database Configuration</h2><p class="mt-2 text-sm text-slate-600">Provide your database connection details.</p></div>
    <div class="p-6 border bg-slate-50/50 border-slate-200 rounded-lg"><div class="space-y-6">
        <div class="form-group"><label for="db_connection" class="block text-sm font-medium text-slate-700">Database Type</label><select id="db_connection" name="db_connection" class="form-input"><option value="mysql" @if(old('db_connection') == 'mysql') selected @endif>MySQL / MariaDB</option><option value="pgsql" @if(old('db_connection') == 'pgsql') selected @endif>PostgreSQL</option><option value="sqlite" @if(old('db_connection') == 'sqlite') selected @endif>SQLite</option></select></div>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6"><div class="sm:col-span-4"><label for="db_host" class="block text-sm font-medium text-slate-700">Database Host</label><input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required class="form-input"></div><div class="sm:col-span-2"><label for="db_port" class="block text-sm font-medium text-slate-700">Port</label><input type="number" id="db_port" name="db_port" value="{{ old('db_port', 3306) }}" required class="form-input"></div></div>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6"><div class="sm:col-span-3"><label for="db_database" class="block text-sm font-medium text-slate-700">Database Name</label><input type="text" id="db_database" name="db_database" value="{{ old('db_database', 'diveforge') }}" required class="form-input"></div><div class="sm:col-span-3"><label for="db_username" class="block text-sm font-medium text-slate-700">Username</label><input type="text" id="db_username" name="db_username" value="{{ old('db_username', 'root') }}" required class="form-input"></div></div>
        <div><label for="db_password" class="block text-sm font-medium text-slate-700">Password</label><input type="password" id="db_password" name="db_password" class="form-input"></div>
    </div></div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step1.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary">Test & Continue</button></div></div>
</form>
@endsection
EOF
# Administrator View
cat << 'EOF' > resources/views/installer/steps/administrator.blade.php
@extends('installer.layout', ['current_step' => 3])
@section('content')
<form action="{{ route('installer.step3.process') }}" method="POST" class="space-y-8">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Create Administrator Account</h2><p class="mt-2 text-sm text-slate-600">Set up the primary administrator for your DiveForge installation.</p></div>
    <div class="p-6 border bg-slate-50/50 border-slate-200 rounded-lg"><div class="space-y-6">
      <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
          <div><label for="first_name" class="block text-sm font-medium text-slate-700">First Name</label><input type="text" name="first_name" value="{{ old('first_name') }}" required class="form-input"></div>
          <div><label for="last_name" class="block text-sm font-medium text-slate-700">Last Name</label><input type="text" name="last_name" value="{{ old('last_name') }}" required class="form-input"></div>
      </div>
      <div><label for="email" class="block text-sm font-medium text-slate-700">Email Address</label><input type="email" name="email" value="{{ old('email') }}" required class="form-input"></div>
      <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
          <div><label for="password" class="block text-sm font-medium text-slate-700">Password</label><input type="password" name="password" required class="form-input"><p class="mt-1 text-xs text-slate-500">Minimum 12 characters.</p></div>
          <div><label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label><input type="password" name="password_confirmation" required class="form-input"></div>
      </div>
    </div></div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step2.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary">Next Step</button></div></div>
</form>
@endsection
EOF
# Shop Setup View
cat << 'EOF' > resources/views/installer/steps/shop-setup.blade.php
@extends('installer.layout', ['current_step' => 4])
@section('content')
<form action="{{ route('installer.step4.process') }}" method="POST" class="space-y-8">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Configure Your Dive Shop</h2><p class="mt-2 text-sm text-slate-600">Set up your dive shop's basic details.</p></div>
    <div class="p-6 border bg-slate-50/50 border-slate-200 rounded-lg"><div class="space-y-6">
        <div><label for="shop_name" class="block text-sm font-medium text-slate-700">Shop Name</label><input type="text" name="shop_name" value="{{ old('shop_name', 'My Dive Shop') }}" required class="form-input"></div>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <div><label for="shop_email" class="block text-sm font-medium text-slate-700">Business Email</label><input type="email" name="shop_email" value="{{ old('shop_email') }}" required class="form-input"></div>
            <div><label for="shop_phone" class="block text-sm font-medium text-slate-700">Business Phone</label><input type="tel" name="shop_phone" value="{{ old('shop_phone') }}" class="form-input"></div>
        </div>
        <div><label for="shop_address" class="block text-sm font-medium text-slate-700">Business Address</label><textarea name="shop_address" rows="3" class="form-input">{{ old('shop_address') }}</textarea></div>
    </div></div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step3.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary">Next Step</button></div></div>
</form>
@endsection
EOF
# Finish View
cat << 'EOF' > resources/views/installer/steps/finish.blade.php
@extends('installer.layout', ['current_step' => 5])
@section('content')
<form action="{{ route('installer.finish') }}" method="POST">
    @csrf
    <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <h2 class="mt-4 text-xl font-bold text-slate-800">Ready to Install!</h2>
        <p class="mt-2 text-sm text-slate-600">Everything is configured. Click the button below to finalize the installation.</p>
    </div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step4.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary font-bold">Run Installation</button></div></div>
</form>
@endsection
EOF
# Complete View
cat << 'EOF' > resources/views/installer/steps/complete.blade.php
@extends('installer.layout', ['current_step' => 5])
@section('content')
<div class="text-center">
    <svg class="mx-auto h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    <h2 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">Installation Complete!</h2>
    <p class="mt-2 text-base text-gray-500">DiveForge has been successfully installed. Your shop is ready to go.</p>
    <p class="mt-4 text-xs text-gray-400">For security, the installer has now been disabled.</p>
    <div class="mt-6"><a href="/" class="btn btn-primary text-base font-bold">Go to Your Dashboard &rarr;</a></div>
</div>
@endsection
EOF
echo_success "All local installer files have been created/updated."

# --- 3. Final Sync (Local -> Server) ---
echo_info "Syncing updated local files to the production server: $PROD_HOST"
# Using explicit excludes instead of a file for reliability
sudo rsync -avz --delete --exclude '.git' --exclude 'vendor' --exclude 'node_modules' --exclude '.env' --chown=$PROD_USER:$PROD_USER -e "ssh" --rsync-path="sudo rsync" ./ "$PROD_HOST:$PROD_PATH"
if [ $? -ne 0 ]; then
    echo_error "rsync failed. Could not sync files to the server."
    exit 1
fi
echo_success "File synchronization complete."

# --- 4. Execute Remote Commands ---
echo_info "Executing setup commands remotely on the server..."
# Using -t flag with ssh to force a pseudo-terminal for sudo password prompt
ssh -t $PROD_HOST "
    echo '--- Running commands on server: $PROD_HOST ---'
    cd $PROD_PATH
    
    if [ ! -f .env ]; then
        echo 'Copying .env.example to .env...'
        sudo cp .env.example .env
    fi

    echo 'Running composer install...'
    sudo composer install --no-dev --optimize-autoloader
    
    echo 'Generating application key...'
    sudo php artisan key:generate --force

    echo 'Setting permissions...'
    sudo chown -R $PROD_USER:$PROD_USER .
    sudo find . -type f -exec chmod 664 {} \;
    sudo find . -type d -exec chmod 775 {} \;
    sudo chmod -R 775 storage bootstrap/cache

    echo 'Clearing caches...'
    sudo php artisan config:clear
    sudo php artisan route:clear
    sudo php artisan view:clear
    sudo php artisan cache:clear
    echo '--- Server setup commands finished ---'
"

if [ $? -eq 0 ]; then
    echo_success "Server commands executed successfully."
else
    echo_error "Failed to execute commands on the server."
fi

echo_info "--------------------------------------------------"
echo_success "Setup Complete! Your installer is ready."
echo_info "Please delete the 'installed' file from '$PROD_PATH/storage/' on the server if it exists."
echo_info "Then visit your site URL to begin the installation."
echo_info "--------------------------------------------------"
