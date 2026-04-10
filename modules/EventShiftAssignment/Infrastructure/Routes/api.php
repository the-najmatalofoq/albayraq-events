<?php
// modules/EventShiftAssignment/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventShiftAssignment\Presentation\Http\Action\CreateShiftAssignmentAction;
use Modules\EventShiftAssignment\Presentation\Http\Action\ListShiftAssignmentsAction;
use Modules\EventShiftAssignment\Presentation\Http\Action\CancelShiftAssignmentAction;

Route::prefix('event-participations/{participationId}/shift-assignments')->group(function () {
    Route::post('/', CreateShiftAssignmentAction::class);
    Route::get('/', ListShiftAssignmentsAction::class);
    Route::delete('/{id}', CancelShiftAssignmentAction::class);
});
