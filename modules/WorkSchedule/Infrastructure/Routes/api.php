<?php
// modules/WorkSchedule/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\WorkSchedule\Presentation\Http\Action\CreateWorkScheduleAction;
use Modules\WorkSchedule\Presentation\Http\Action\UpdateWorkScheduleAction;
use Modules\WorkSchedule\Presentation\Http\Action\DeleteWorkScheduleAction;
use Modules\WorkSchedule\Presentation\Http\Action\GetWorkScheduleAction;
use Modules\WorkSchedule\Presentation\Http\Action\ListWorkSchedulesPaginationAction;

Route::get('/', ListWorkSchedulesPaginationAction::class);
Route::post('/', CreateWorkScheduleAction::class);
Route::get('/{id}', GetWorkScheduleAction::class);
Route::put('/{id}', UpdateWorkScheduleAction::class);
Route::delete('/{id}', DeleteWorkScheduleAction::class);
