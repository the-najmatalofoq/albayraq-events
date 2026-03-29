<?php

use Illuminate\Support\Facades\Route;

Route::prefix('contract-acceptance-steps')
    ->middleware(['api'])
    ->group(function () {
        // Define ContractAcceptanceStep routes here
        // Example:
        // Route::get('/', [ContractAcceptanceStepController::class, 'index']);
    });
