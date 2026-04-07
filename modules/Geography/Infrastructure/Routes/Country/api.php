<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Geography\Presentation\Http\Action\{
    CreateCountryAction,
    UpdateCountryAction,
    DeleteCountryAction,
    ListCountriesAction,
    ListStatesAction,
    ListCitiesByCountryAction
};

Route::prefix('countries')->group(function () {
    Route::get('', ListCountriesAction::class);
    Route::post('', CreateCountryAction::class);
    Route::put('/{id}', UpdateCountryAction::class);
    Route::delete('/{id}', DeleteCountryAction::class);
    Route::get('/{countryId}/states', ListStatesAction::class);
    Route::get('/{countryId}/cities', ListCitiesByCountryAction::class);
});
