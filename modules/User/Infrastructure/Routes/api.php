<?php
// modules/User/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\{
    MeAction,
    UpdateProfileAction,
    UpdateAvatarAction,
    UpdateBankDetailsAction,
    AddContactPhoneAction,
    DeleteContactPhoneAction
};

Route::get('/', MeAction::class);
Route::patch('/profile', UpdateProfileAction::class);
Route::post('/avatar', UpdateAvatarAction::class);
Route::patch('/bank', UpdateBankDetailsAction::class);

Route::prefix('contact-phones')->group(function () {
    Route::post('/', AddContactPhoneAction::class);
    Route::delete('/{id}', DeleteContactPhoneAction::class);
});
