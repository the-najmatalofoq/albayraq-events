<?php
// modules\User\Infrastructure\Routes\EmployeeProfile\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\UpdateProfileAction;

Route::patch('/profile', UpdateProfileAction::class);
