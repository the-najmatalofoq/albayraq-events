<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\HealthModule\Presentation\Http\Action\CheckHealthAction;

Route::get('/', CheckHealthAction::class);
Route::get('/alive', CheckHealthAction::class);
Route::get('/ready', CheckHealthAction::class);
Route::get('/live', CheckHealthAction::class);
