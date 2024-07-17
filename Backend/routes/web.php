<?php

use App\Http\Controllers\Auth\VerifyEmailController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');
