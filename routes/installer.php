<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Installer\InstallationController;

/*
|--------------------------------------------------------------------------
| DiveForge Installation Routes
|--------------------------------------------------------------------------
|
| These routes handle the DiveForge installation process. They are only
| active when the application has not been installed yet. Once installed,
| these routes will be disabled for security purposes.
|
*/

// Installation middleware to prevent access after installation
Route::middleware(['web', 'installer.not_installed'])->prefix('install')->name('installer.')->group(function () {
    
    // Step 1: Welcome & System Requirements
    Route::get('/', [InstallationController::class, 'showStep1'])->name('step1.show');
    Route::post('/step1', [InstallationController::class, 'processStep1'])->name('step1.process');
    
    // Step 2: Database Configuration
    Route::get('/database', [InstallationController::class, 'showStep2'])->name('step2.show');
    Route::post('/database', [InstallationController::class, 'processStep2'])->name('step2.process');
    
    // Step 3: Administrator Account Setup
    Route::get('/administrator', [InstallationController::class, 'showStep3'])->name('step3.show');
    Route::post('/administrator', [InstallationController::class, 'processStep3'])->name('step3.process');
    
    // Step 4: Dive Shop Configuration
    Route::get('/shop-setup', [InstallationController::class, 'showStep4'])->name('step4.show');
    Route::post('/shop-setup', [InstallationController::class, 'processStep4'])->name('step4.process');
    
    // Step 5: Final Installation & Review
    Route::get('/finish', [InstallationController::class, 'showFinish'])->name('finish.show');
    Route::post('/finish', [InstallationController::class, 'processFinish'])->name('finish.process');
    
    // Completion Page
    Route::get('/complete', [InstallationController::class, 'showComplete'])->name('complete');
    
    // AJAX Endpoints for Real-time Testing
    Route::prefix('ajax')->name('ajax.')->group(function () {
        
        // Test database connection in real-time
        Route::post('/test-database', [InstallationController::class, 'testConnection'])->name('test.database');
        
        // Check system requirements
        Route::get('/check-requirements', [InstallationController::class, 'checkSystemRequirements'])->name('check.requirements');
        
        // Validate admin credentials
        Route::post('/validate-admin', [InstallationController::class, 'validateAdminData'])->name('validate.admin');
        
        // Test email configuration
        Route::post('/test-email', [InstallationController::class, 'testEmailConfig'])->name('test.email');
        
        // Check shop name availability
        Route::post('/check-shop-name', [InstallationController::class, 'checkShopName'])->name('check.shop_name');
        
        // Get installation progress
        Route::get('/progress/{installationId}', [InstallationController::class, 'getInstallationProgress'])->name('progress');
    });
    
    // Installation Utilities
    Route::prefix('utils')->name('utils.')->group(function () {
        
        // Download system info for support
        Route::get('/system-info', [InstallationController::class, 'downloadSystemInfo'])->name('system_info');
        
        // Reset installation (emergency)
        Route::post('/reset', [InstallationController::class, 'resetInstallation'])->name('reset');
        
        // Test file permissions
        Route::get('/test-permissions', [InstallationController::class, 'testFilePermissions'])->name('test.permissions');
        
        // Generate encryption key
        Route::post('/generate-key', [InstallationController::class, 'generateAppKey'])->name('generate.key');
    });
    
    // Installation Steps API (for progress tracking)
    Route::prefix('api')->name('api.')->group(function () {
        
        // Get current installation step
        Route::get('/current-step', [InstallationController::class, 'getCurrentStep'])->name('current_step');
        
        // Get installation session data
        Route::get('/session-data', [InstallationController::class, 'getSessionData'])->name('session_data');
        
        // Update installation progress
        Route::post('/update-progress', [InstallationController::class, 'updateProgress'])->name('update_progress');
        
        // Get supported timezones
        Route::get('/timezones', [InstallationController::class, 'getSupportedTimezones'])->name('timezones');
        
        // Get supported currencies
        Route::get('/currencies', [InstallationController::class, 'getSupportedCurrencies'])->name('currencies');
        
        // Get database drivers
        Route::get('/database-drivers', [InstallationController::class, 'getSupportedDatabases'])->name('database_drivers');
    });
    
    // Installation Logs (for debugging)
    Route::prefix('logs')->name('logs.')->group(function () {
        
        // View installation logs
        Route::get('/', [InstallationController::class, 'viewInstallationLogs'])->name('view');
        
        // Download installation logs
        Route::get('/download', [InstallationController::class, 'downloadInstallationLogs'])->name('download');
        
        // Clear installation logs
        Route::delete('/clear', [InstallationController::class, 'clearInstallationLogs'])->name('clear');
    });
});

// Fallback route - redirect to installation if not installed
Route::fallback(function () {
    // Check if the app is installed
    if (!file_exists(storage_path('installed'))) {
        return redirect()->route('installer.step1.show');
    }
    
    // If installed but route not found, return 404
    abort(404);
})->middleware('web');

// Health check endpoint for installation
Route::get('/install-health', function () {
    $systemCheck = app(InstallationController::class)->checkSystemRequirements();
    
    return response()->json([
        'status' => $systemCheck['all_passed'] ? 'ready' : 'not_ready',
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'requirements' => $systemCheck,
        'installed' => file_exists(storage_path('installed')),
        'timestamp' => now()->toISOString()
    ]);
})->middleware('web');

// Installation status endpoint
Route::get('/install-status', function () {
    $installedFile = storage_path('installed');
    
    if (!file_exists($installedFile)) {
        return response()->json([
            'installed' => false,
            'message' => 'DiveForge is not installed yet',
            'install_url' => route('installer.step1.show')
        ]);
    }
    
    $installationData = json_decode(file_get_contents($installedFile), true);
    
    return response()->json([
        'installed' => true,
        'installation_data' => $installationData,
        'message' => 'DiveForge is installed and ready'
    ]);
})->middleware('web');

// Emergency installation reset (only in debug mode)
Route::post('/emergency-reset', function () {
    if (!config('app.debug')) {
        abort(403, 'Emergency reset only available in debug mode');
    }
    
    try {
        // Remove installation marker
        if (file_exists(storage_path('installed'))) {
            unlink(storage_path('installed'));
        }
        
        // Clear all sessions
        session()->flush();
        
        // Clear caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'Installation reset successfully',
            'redirect_url' => route('installer.step1.show')
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to reset installation: ' . $e->getMessage()
        ], 500);
    }
})->middleware('web');