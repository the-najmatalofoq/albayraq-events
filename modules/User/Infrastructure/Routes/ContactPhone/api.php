<?php
// modules\User\Infrastructure\Routes\ContactPhone\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\{
    GetContactPhonesAction,
    GetContactPhoneAction,
    AddContactPhoneAction,
    UpdateContactPhoneAction,
    DeleteContactPhoneAction,
    BulkDeleteContactPhonesAction
};

Route::prefix('contact-phones')->group(function (): void {
    Route::get('/', GetContactPhonesAction::class);
    Route::get('/{id}', GetContactPhoneAction::class);
    Route::post('/', AddContactPhoneAction::class);
    Route::put('/{id}', UpdateContactPhoneAction::class);
    Route::delete('/{id}', DeleteContactPhoneAction::class);
    Route::delete('/', BulkDeleteContactPhonesAction::class);
});
