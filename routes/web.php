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
