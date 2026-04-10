<?php
// modules/EventStaffingPosition/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventStaffingPosition\Presentation\Http\Action\CreateEventStaffingPositionAction;
use Modules\EventStaffingPosition\Presentation\Http\Action\ListEventStaffingPositionsAction;
use Modules\EventStaffingPosition\Presentation\Http\Action\ShowEventStaffingPositionAction;
use Modules\EventStaffingPosition\Presentation\Http\Action\DeleteEventStaffingPositionAction;

Route::prefix('v1/events/{eventId}/positions')->group(function () {
    Route::post('/', CreateEventStaffingPositionAction::class);
    Route::get('/', ListEventStaffingPositionsAction::class);
    Route::get('/{id}', ShowEventStaffingPositionAction::class);
    Route::delete('/{id}', DeleteEventStaffingPositionAction::class);
});
