// routes/web.php
Route::prefix('install')->name('installer.')->group(function () {
    Route::get('/', [InstallationController::class, 'showStep1'])->name('step1.show');
    Route::post('/step1', [InstallationController::class, 'processStep1'])->name('step1.process');
    Route::get('/step2', [InstallationController::class, 'showStep2'])->name('step2.show');
    Route::post('/step2', [InstallationController::class, 'processStep2'])->name('step2.process');
    Route::get('/step3', [InstallationController::class, 'showStep3'])->name('step3.show');
    Route::post('/step3', [InstallationController::class, 'processStep3'])->name('step3.process');
    Route::get('/step4', [InstallationController::class, 'showStep4'])->name('step4.show');
    Route::post('/step4', [InstallationController::class, 'processStep4'])->name('step4.process');
    Route::get('/step5', [InstallationController::class, 'showFinalStep'])->name('step5.show');
    Route::post('/finish', [InstallationController::class, 'finishInstallation'])->name('finish');
    Route::get('/complete', [InstallationController::class, 'showCompletionPage'])->name('complete');
    
    // AJAX endpoints
    Route::post('/test-database', [InstallationController::class, 'testDatabase'])->name('test.database');
    Route::get('/check-requirements', [InstallationController::class, 'checkRequirements'])->name('check.requirements');
});