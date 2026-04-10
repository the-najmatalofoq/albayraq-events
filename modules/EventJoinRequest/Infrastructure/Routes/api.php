<?php
// modules/EventJoinRequest/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventJoinRequest\Presentation\Http\Action\CreateEventJoinRequestAction;
use Modules\EventJoinRequest\Presentation\Http\Action\ListEventJoinRequestsAction;
use Modules\EventJoinRequest\Presentation\Http\Action\ShowEventJoinRequestAction;
use Modules\EventJoinRequest\Presentation\Http\Action\ReviewEventJoinRequestAction;
use Modules\EventJoinRequest\Presentation\Http\Action\DeleteEventJoinRequestAction;

Route::prefix('v1/events/{eventId}/join-requests')->group(function () {
    Route::post('/', CreateEventJoinRequestAction::class);
    Route::get('/', ListEventJoinRequestsAction::class);
    Route::get('/{id}', ShowEventJoinRequestAction::class);
    Route::patch('/{id}/review', ReviewEventJoinRequestAction::class);
    Route::delete('/{id}', DeleteEventJoinRequestAction::class);
});
