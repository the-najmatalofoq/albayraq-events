<?php

use Illuminate\Support\Facades\Route;

Route::prefix('event-expenses')
    ->middleware(['api'])
    ->group(function () {
        // Define EventExpense routes here
        // Example:
        // Route::get('/', [EventExpenseController::class, 'index']);
    });
