<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\{MeAction, UpdateAvatarAction};

Route::get('/', MeAction::class);
Route::post('/avatar', UpdateAvatarAction::class);
