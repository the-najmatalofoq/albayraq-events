<?php
// modules/EventShift/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventShift\Presentation\Http\Action\CreateShiftAction;
use Modules\EventShift\Presentation\Http\Action\ListShiftsAction;
use Modules\EventShift\Presentation\Http\Action\ShowShiftAction;
use Modules\EventShift\Presentation\Http\Action\UpdateShiftAction;
use Modules\EventShift\Presentation\Http\Action\DeleteShiftAction;

Route::prefix('{eventId}/shifts')->group(function () {
    Route::post('/', CreateShiftAction::class);
    Route::get('/', ListShiftsAction::class);
    Route::get('/{id}', ShowShiftAction::class);
    Route::patch('/{id}', UpdateShiftAction::class);
    Route::delete('/{id}', DeleteShiftAction::class);
});
