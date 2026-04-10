<?php
// modules/EventShift/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventShift\Presentation\Http\Action\CreateShiftAction;
use Modules\EventShift\Presentation\Http\Action\ListShiftsAction;

Route::prefix('events/{eventId}/shifts')->group(function () {
    Route::post('/', CreateShiftAction::class);
    Route::get('/', ListShiftsAction::class);
});
