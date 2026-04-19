<?php
// modules/PenaltyType/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\PenaltyType\Presentation\Http\Action\Dashboard\ListPenaltyTypesAction;
use Modules\PenaltyType\Presentation\Http\Action\Dashboard\ListPenaltyTypesPaginatedAction;
use Modules\PenaltyType\Presentation\Http\Action\Dashboard\CreatePenaltyTypeAction;
use Modules\PenaltyType\Presentation\Http\Action\Dashboard\GetPenaltyTypeByIdAction;
use Modules\PenaltyType\Presentation\Http\Action\Dashboard\UpdatePenaltyTypeAction;
use Modules\PenaltyType\Presentation\Http\Action\Dashboard\DeletePenaltyTypeAction;

Route::get('/', ListPenaltyTypesAction::class);
Route::get('/paginated', ListPenaltyTypesPaginatedAction::class);
Route::post('/', CreatePenaltyTypeAction::class);
Route::get('/{id}', GetPenaltyTypeByIdAction::class);
Route::put('/{id}', UpdatePenaltyTypeAction::class);
Route::delete('/{id}', DeletePenaltyTypeAction::class);
