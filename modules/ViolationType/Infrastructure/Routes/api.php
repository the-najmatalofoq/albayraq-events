<?php
// modules/ViolationType/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ViolationType\Presentation\Http\Action\CreateViolationTypeAction;
use Modules\ViolationType\Presentation\Http\Action\UpdateViolationTypeAction;
use Modules\ViolationType\Presentation\Http\Action\DeleteViolationTypeAction;
use Modules\ViolationType\Presentation\Http\Action\GetViolationTypeAction;
use Modules\ViolationType\Presentation\Http\Action\ListViolationTypesPaginationAction;

Route::get('/', ListViolationTypesPaginationAction::class);
Route::post('/', CreateViolationTypeAction::class);
Route::get('/{id}', GetViolationTypeAction::class);
Route::put('/{id}', UpdateViolationTypeAction::class);
Route::delete('/{id}', DeleteViolationTypeAction::class);
