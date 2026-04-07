<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Geography\Presentation\Http\Action\{
    CreateStateAction,
    UpdateStateAction,
    DeleteStateAction,
    ListStatesAction,
    ListCitiesByStateAction
};

Route::prefix('states')->group(function () {
    Route::get('', ListStatesAction::class);
    Route::post('', CreateStateAction::class);
    Route::put('/{id}', UpdateStateAction::class);
    Route::delete('/{id}', DeleteStateAction::class);
    Route::get('/{stateId}/cities', ListCitiesByStateAction::class);
});
