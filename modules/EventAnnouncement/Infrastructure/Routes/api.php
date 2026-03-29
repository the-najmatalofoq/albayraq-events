<?php

use Illuminate\Support\Facades\Route;

Route::prefix('event-announcements')
    ->middleware(['api'])
    ->group(function () {
        // Define EventAnnouncement routes here
        // Example:
        // Route::get('/', [EventAnnouncementController::class, 'index']);
    });
