<?php
// modules/Wage/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Wage\Presentation\Http\Action\CreateWageAction;
use Modules\Wage\Presentation\Http\Action\ListWagesAction;
use Modules\Wage\Presentation\Http\Action\ShowWageAction;
use Modules\Wage\Presentation\Http\Action\UpdateWageAction;
use Modules\Wage\Presentation\Http\Action\DeleteWageAction;

Route::post('/', CreateWageAction::class);
Route::get('/', ListWagesAction::class);
Route::get('/{id}', ShowWageAction::class);
Route::patch('/{id}', UpdateWageAction::class);
Route::delete('/{id}', DeleteWageAction::class);
