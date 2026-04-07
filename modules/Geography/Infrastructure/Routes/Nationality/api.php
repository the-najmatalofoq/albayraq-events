<?php
// modules\Geography\Infrastructure\Routes\Nationality\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Geography\Presentation\Http\Action\{
    CreateNationalityAction,
    UpdateNationalityAction,
    DeleteNationalityAction,
    ListNationalitiesAction
};

Route::prefix('nationalities')->group(function () {
    Route::get('', ListNationalitiesAction::class);
    Route::post('', CreateNationalityAction::class);
    Route::put('/{id}', UpdateNationalityAction::class);
    Route::delete('/{id}', DeleteNationalityAction::class);
});
