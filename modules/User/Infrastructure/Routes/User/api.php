<?php
// modules\User\Infrastructure\Routes\User\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\{
    MeAction,
    UpdateAvatarAction,
    UpdateMeAction,
    DeleteAccountAction,
    UpdatePasswordAction,
    UpdateEmailAction
};

Route::get('/', MeAction::class);
Route::put('/', UpdateMeAction::class);
Route::delete('/', DeleteAccountAction::class);
Route::post('/avatar', UpdateAvatarAction::class);
Route::put('/password', UpdatePasswordAction::class);
Route::put('/email', UpdateEmailAction::class);
