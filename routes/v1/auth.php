<?php

use App\Http\Controllers\V1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\V1\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\V1\Auth\NewPasswordController;
use App\Http\Controllers\V1\Auth\PasswordResetLinkController;
use App\Http\Controllers\V1\Auth\RegisteredUserController;
use App\Http\Controllers\V1\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                    ->middleware('guest');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->middleware('guest')
                    ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
                    ->middleware('guest')
                    ->name('password.store');

    Route::get('/profile', [AuthenticatedSessionController::class, 'show'])
                    ->middleware('auth:sanctum')
                    ->name('profile');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->middleware('auth:sanctum')
                    ->name('logout');
});
