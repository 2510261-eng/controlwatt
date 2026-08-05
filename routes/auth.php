Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
