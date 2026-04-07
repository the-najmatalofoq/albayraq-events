<?php
// modules\Geography\Infrastructure\Routes\City\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Geography\Presentation\Http\Action\CreateCityAction;
use Modules\Geography\Presentation\Http\Action\DeleteCityAction;
use Modules\Geography\Presentation\Http\Action\UpdateCityAction;

Route::prefix('cities')->group(function () {
    Route::post('', CreateCityAction::class);
    Route::put('/{id}', UpdateCityAction::class);
    Route::delete('/{id}', DeleteCityAction::class);
});
