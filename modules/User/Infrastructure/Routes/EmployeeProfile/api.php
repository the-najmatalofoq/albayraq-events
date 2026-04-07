<?php
// modules\User\Infrastructure\Routes\EmployeeProfile\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\{GetProfileAction, UpdateProfileAction, DeleteProfileAction};

Route::get('/profile', GetProfileAction::class);
Route::put('/profile', UpdateProfileAction::class);
Route::delete('/profile', DeleteProfileAction::class);
