<?php
// modules/EventStaffingGroup/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventStaffingGroup\Presentation\Http\Action\CreateEventStaffingGroupAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\ListEventStaffingGroupsAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\ShowEventStaffingGroupAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\UpdateEventStaffingGroupAction;
use Modules\EventStaffingGroup\Presentation\Http\Action\DeleteEventStaffingGroupAction;

Route::prefix('{eventId}/groups')->group(function () {
    Route::post('/', CreateEventStaffingGroupAction::class);
    Route::get('/', ListEventStaffingGroupsAction::class);
    Route::get('/{id}', ShowEventStaffingGroupAction::class);
    Route::patch('/{id}', UpdateEventStaffingGroupAction::class);
    Route::delete('/{id}', DeleteEventStaffingGroupAction::class);
});
