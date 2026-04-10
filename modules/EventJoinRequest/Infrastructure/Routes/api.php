<?php
// modules/EventJoinRequest/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventJoinRequest\Presentation\Http\Action\CreateJoinRequestAction;
use Modules\EventJoinRequest\Presentation\Http\Action\ListJoinRequestsAction;
use Modules\EventJoinRequest\Presentation\Http\Action\ReviewJoinRequestAction;

Route::prefix('events/{eventId}/join-requests')->group(function () {
    Route::post('/', CreateJoinRequestAction::class);
    Route::get('/', ListJoinRequestsAction::class);
    Route::patch('/{id}/review', ReviewJoinRequestAction::class);
});
