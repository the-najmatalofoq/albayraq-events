<?php
// modules\User\Infrastructure\Routes\ContactPhone\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\ContactPhone\{
    GetContactPhoneAction,
    UpdateContactPhoneAction,
    DeleteContactPhoneAction,
};

Route::prefix('contact-phone')->group(function (): void {
    Route::get('/', GetContactPhoneAction::class);
    Route::put('/', UpdateContactPhoneAction::class);
    Route::delete('/', DeleteContactPhoneAction::class);
});
