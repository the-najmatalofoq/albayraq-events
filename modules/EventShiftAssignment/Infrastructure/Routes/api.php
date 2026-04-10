<?php
// modules/EventShiftAssignment/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventShiftAssignment\Presentation\Http\Action\AssignToShiftAction;

Route::prefix('events/{eventId}')->group(function () {
    Route::post('/shifts/{shiftId}/assignments', AssignToShiftAction::class);
});
