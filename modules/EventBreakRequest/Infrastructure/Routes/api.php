<?php
// modules/EventBreakRequest/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventBreakRequest\Presentation\Http\Action\Dashboard\{
    RequestBreakAction,
    ListBreakRequestsAction,
    ApproveBreakAction,
    RejectBreakAction,
    CancelBreakAction
};
use Modules\EventBreakRequest\Presentation\Http\Action\{
    GetEmployeeTodayBreaksAction,
    GetEmployeeRemainingBreakTimeAction,
    GetAvailableBreakSlotsAction,
    QuickRequestBreakAction
};

// Dashboard Routes
Route::prefix('v1/Crm/events/{eventId}')->group(function () {
    Route::prefix('break-requests')->group(function () {
        Route::get('/', ListBreakRequestsAction::class);
        Route::post('/', RequestBreakAction::class);
        Route::patch('/{id}/approve', ApproveBreakAction::class);
        Route::patch('/{id}/reject', RejectBreakAction::class);
        Route::delete('/{id}', CancelBreakAction::class);
    });
});

// Mobile Routes
Route::prefix('v1/mobile')->group(function () {
    Route::prefix('my-breaks')->group(function () {
        Route::get('/today', GetEmployeeTodayBreaksAction::class);
        Route::get('/remaining', GetEmployeeRemainingBreakTimeAction::class);
    });

    Route::get('/available-break-slots', GetAvailableBreakSlotsAction::class);
    Route::post('/breaks/quick-request', QuickRequestBreakAction::class);
});
