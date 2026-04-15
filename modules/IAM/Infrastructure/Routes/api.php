<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\IAM\Presentation\Http\Action\{
    LoginAction,
    LogoutAction,
    RegisterAction,
    RefreshTokenAction,
    ForgotPasswordAction,
    ResetPasswordAction,
    SendEmailVerificationAction,
    VerifyEmailAction,
};

Route::post('/register', RegisterAction::class);
Route::post('/login', LoginAction::class);

Route::post('/forgot-password', ForgotPasswordAction::class);
Route::post('/reset-password', ResetPasswordAction::class);

Route::middleware(['auth:api', 'session.validate'])->group(function () {
    Route::post('/logout', LogoutAction::class);
    Route::post('/refresh', RefreshTokenAction::class);

    Route::post('/email/send-verification', SendEmailVerificationAction::class);
    Route::post('/email/verify', VerifyEmailAction::class);
});
