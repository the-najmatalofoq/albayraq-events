<?php
// modules/EventStaffingGroup/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventStaffingGroup\Presentation\Http\Action\CreateEventStaffingGroupAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\ListEventStaffingGroupsAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\ShowEventStaffingGroupAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\DeleteEventStaffingGroupAction;

Route::prefix('v1/events/{eventId}/groups')->group(function () {
    Route::post('/', CreateEventStaffingGroupAction::class);
    Route::get('/', ListEventStaffingGroupsAction::class);
    Route::get('/{id}', ShowEventStaffingGroupAction::class);
    Route::delete('/{id}', DeleteEventStaffingGroupAction::class);
});
