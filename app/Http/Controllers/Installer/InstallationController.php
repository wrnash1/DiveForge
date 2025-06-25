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
