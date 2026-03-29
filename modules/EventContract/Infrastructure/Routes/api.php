<?php

use Illuminate\Support\Facades\Route;

Route::prefix('event-contracts')
    ->middleware(['api'])
    ->group(function () {
        // Define EventContract routes here
        // Example:
        // Route::get('/', [EventContractController::class, 'index']);
    });
