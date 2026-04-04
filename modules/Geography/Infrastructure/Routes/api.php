<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Geography\Presentation\Http\Action\ListCountriesAction;
use Modules\Geography\Presentation\Http\Action\ListStatesAction;
use Modules\Geography\Presentation\Http\Action\ListCitiesAction;
use Modules\Geography\Presentation\Http\Action\ListNationalitiesAction;

// we must have the full crud for all the them.
Route::prefix('geography')->group(function () {
    Route::get('/countries', ListCountriesAction::class);
    Route::get('/countries/{countryId}/states', ListStatesAction::class);
    Route::get('/states/{stateId}/cities', ListCitiesAction::class);
    Route::get('/countries/{countryId}/cities', ListCitiesAction::class);

    // nationalities can be filtered by ?country_id=
    Route::get('/nationalities', ListNationalitiesAction::class);
});
