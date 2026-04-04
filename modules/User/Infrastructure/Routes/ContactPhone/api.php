<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\{AddContactPhoneAction, DeleteContactPhoneAction};

Route::prefix('contact-phones')->group(function (): void {
    Route::post('/', AddContactPhoneAction::class);
    Route::delete('/{id}', DeleteContactPhoneAction::class);
});
