<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Notification\Presentation\Http\Action;

Route::prefix('v1/me')->middleware(['auth:api'])->group(function () {
    Route::post('/device-tokens', Action\RegisterDeviceTokenAction::class);
    Route::delete('/device-tokens/{id}', Action\RevokeDeviceTokenAction::class);
    Route::get('/notifications', Action\ListNotificationsAction::class);
    Route::patch('/notifications/{id}/read', Action\MarkNotificationReadAction::class);
    Route::patch('/notifications/read-all', Action\MarkAllNotificationsReadAction::class);
    Route::get('/notifications/unread-count', Action\UnreadCountAction::class);
});

Route::prefix('v1/events/{eventId}')->middleware(['auth:api'])->group(function () {
    Route::post('/location', Action\UpdateLocationAction::class);
});
