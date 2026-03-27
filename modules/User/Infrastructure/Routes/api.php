<?php
// modules/User/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\GetUserProfileAction;

Route::get('/me', GetUserProfileAction::class);
