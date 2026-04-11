<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\Dashboard\{
    ListUsersPaginatedAction,
    ListUsersAction,
    GetUserByIdAction,
    CreateUserAction,
    UpdateUserAction,
    DeleteUserAction,
    ListProfilesPaginatedAction,
    GetProfileByIdAction,
    ListBankDetailsAction,
    UpdateBankDetailAction,
    ListContactPhonesAction,
};


Route::get('/', ListUsersPaginatedAction::class);
Route::get('/all', ListUsersAction::class);
Route::get('/{id}', GetUserByIdAction::class);
Route::post('/', CreateUserAction::class);
Route::put('/{id}', UpdateUserAction::class);
Route::delete('/{id}', DeleteUserAction::class);

Route::prefix('/profiles')->group(function () {
    Route::get('/', ListProfilesPaginatedAction::class);
    Route::get('/{id}', GetProfileByIdAction::class);
});

Route::prefix('/bank-details')->group(function () {
    Route::get('/', ListBankDetailsAction::class);
    Route::put('/{id}', UpdateBankDetailAction::class);
});

Route::prefix('/contact-phones')->group(function () {
    Route::get('/', ListContactPhonesAction::class);
});
