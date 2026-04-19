<?php
// modules/DeductionType/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\DeductionType\Presentation\Http\Action\Dashboard\ListDeductionTypesAction;
use Modules\DeductionType\Presentation\Http\Action\Dashboard\ListDeductionTypesPaginatedAction;
use Modules\DeductionType\Presentation\Http\Action\Dashboard\CreateDeductionTypeAction;
use Modules\DeductionType\Presentation\Http\Action\Dashboard\GetDeductionTypeByIdAction;
use Modules\DeductionType\Presentation\Http\Action\Dashboard\UpdateDeductionTypeAction;
use Modules\DeductionType\Presentation\Http\Action\Dashboard\DeleteDeductionTypeAction;

Route::get('/', ListDeductionTypesAction::class);
Route::get('/paginated', ListDeductionTypesPaginatedAction::class);
Route::post('/', CreateDeductionTypeAction::class);
Route::get('/{id}', GetDeductionTypeByIdAction::class);
Route::put('/{id}', UpdateDeductionTypeAction::class);
Route::delete('/{id}', DeleteDeductionTypeAction::class);
