<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Geography\Presentation\Http\Action\{
    CreateCityAction,
    CreateCountryAction,
    CreateNationalityAction,
    CreateStateAction,
    DeleteCityAction,
    DeleteCountryAction,
    DeleteNationalityAction,
    DeleteStateAction,
    UpdateCityAction,
    UpdateCountryAction,
    UpdateNationalityAction,
    UpdateStateAction,
    ListCountriesAction,
    ListStatesAction,
    ListCitiesByCountryAction,
    ListCitiesByStateAction,
    ListNationalitiesAction
};

Route::prefix('admin/geography')
    ->middleware(['auth:api', 'role:system_controller'])
    ->group(function () {

        Route::prefix('countries')->group(function () {
            Route::get('/', ListCountriesAction::class);
            Route::post('/', CreateCountryAction::class);
            Route::put('/{id}', UpdateCountryAction::class);
            Route::delete('/{id}', DeleteCountryAction::class);

            Route::get('/{countryId}/states', ListStatesAction::class);

            Route::get('/{countryId}/cities', ListCitiesByCountryAction::class);
        });

        Route::prefix('states')->group(function () {
            Route::post('/', CreateStateAction::class);
            Route::put('/{id}', UpdateStateAction::class);
            Route::delete('/{id}', DeleteStateAction::class);

            Route::get('/{stateId}/cities', ListCitiesByStateAction::class);
        });

        Route::prefix('cities')->group(function () {
            Route::post('/', CreateCityAction::class);
            Route::put('/{id}', UpdateCityAction::class);
            Route::delete('/{id}', DeleteCityAction::class);
        });

        Route::prefix('nationalities')->group(function () {
            Route::get('/', ListNationalitiesAction::class);
            Route::post('/', CreateNationalityAction::class);
            Route::put('/{id}', UpdateNationalityAction::class);
            Route::delete('/{id}', DeleteNationalityAction::class);
        });
    });

Route::prefix('geography')->group(function () {

    Route::prefix('countries')->group(function () {
        Route::get('/', ListCountriesAction::class);
        Route::get('/{countryId}/states', ListStatesAction::class);
        Route::get('/{countryId}/cities', ListCitiesByCountryAction::class);
    });

    Route::prefix('states')->group(function () {
        Route::get('/{stateId}/cities', ListCitiesByStateAction::class);
    });

    Route::get('/nationalities', ListNationalitiesAction::class);
});