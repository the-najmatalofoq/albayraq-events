<?php
// modules/Currency/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Currency\Presentation\Http\Action\Dashboard\ListCurrenciesAction;
use Modules\Currency\Presentation\Http\Action\Dashboard\ListCurrenciesPaginatedAction;
use Modules\Currency\Presentation\Http\Action\Dashboard\ShowCurrencyAction;
use Modules\Currency\Presentation\Http\Action\Dashboard\CreateCurrencyAction;
use Modules\Currency\Presentation\Http\Action\Dashboard\UpdateCurrencyAction;
use Modules\Currency\Presentation\Http\Action\Dashboard\DeleteCurrencyAction;

Route::get('/', ListCurrenciesAction::class);
Route::get('/paginated', ListCurrenciesPaginatedAction::class);
Route::get('/{currency}', ShowCurrencyAction::class);
Route::post('/', CreateCurrencyAction::class);
Route::put('/{currency}', UpdateCurrencyAction::class);
Route::delete('/{currency}', DeleteCurrencyAction::class);
