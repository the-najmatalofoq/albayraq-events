<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ViolationType\Presentation\Http\Action\Dashboard\ListViolationTypesAction;
use Modules\ViolationType\Presentation\Http\Action\Dashboard\ListViolationTypesPaginatedAction;
use Modules\ViolationType\Presentation\Http\Action\Dashboard\CreateViolationTypeAction;
use Modules\ViolationType\Presentation\Http\Action\Dashboard\GetViolationTypeByIdAction;
use Modules\ViolationType\Presentation\Http\Action\Dashboard\UpdateViolationTypeAction;
use Modules\ViolationType\Presentation\Http\Action\Dashboard\DeleteViolationTypeAction;

Route::prefix('crm')
    ->middleware(['role.level:admin,super-admin'])
    ->group(function () {
        Route::get('/', ListViolationTypesAction::class);
        Route::get('/paginated', ListViolationTypesPaginatedAction::class);
        Route::post('/', CreateViolationTypeAction::class);
        Route::get('/{id}', GetViolationTypeByIdAction::class);
        Route::put('/{id}', UpdateViolationTypeAction::class);
        Route::delete('/{id}', DeleteViolationTypeAction::class);
    });
