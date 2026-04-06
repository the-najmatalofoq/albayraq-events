<?php
// modules/IAM/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\IAM\Presentation\Http\Action\{
    LoginAction,
    LogoutAction,
    RegisterAction,
    RefreshTokenAction
};

Route::prefix('auth')->middleware(['throttle:auth'])->group(function () {
    Route::post('/register', RegisterAction::class);
    Route::post('/login', LoginAction::class);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', LogoutAction::class);
        Route::post('/refresh', RefreshTokenAction::class);
    });
});
