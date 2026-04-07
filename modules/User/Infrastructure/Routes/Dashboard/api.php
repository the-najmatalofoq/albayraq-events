<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\Dashboard\{
    ListUsersPaginatedCommand,
    ListUsersCommand,
    GetUserByIdCommand,
    CreateUserCommand,
    UpdateUserCommand,
    DeleteUserCommand,
    ListProfilesPaginatedCommand,
    GetProfileByIdCommand,
    ListBankDetailsCommand,
    UpdateBankDetailCommand,
    ListContactPhonesCommand,
};

Route::prefix('/users')->group(function () {
    Route::get('/', ListUsersPaginatedCommand::class);
    Route::get('/all', ListUsersCommand::class);
    Route::get('/{id}', GetUserByIdCommand::class);
    Route::post('/', CreateUserCommand::class);
    Route::put('/{id}', UpdateUserCommand::class);
    Route::delete('/{id}', DeleteUserCommand::class);
});

Route::prefix('/profiles')->group(function () {
    Route::get('/', ListProfilesPaginatedCommand::class);
    Route::get('/{id}', GetProfileByIdCommand::class);
});

Route::prefix('/bank-details')->group(function () {
    Route::get('/', ListBankDetailsCommand::class);
    Route::put('/{id}', UpdateBankDetailCommand::class);
});

Route::prefix('/contact-phones')->group(function () {
    Route::get('/', ListContactPhonesCommand::class);
});
