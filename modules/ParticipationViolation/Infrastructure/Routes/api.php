<?php
// modules/ParticipationViolation/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ParticipationViolation\Presentation\Http\Action\{
    ListParticipationViolationsAction,
    CreateParticipationViolationAction,
    GetParticipationViolationByIdAction,
    UpdateParticipationViolationAction,
    DeleteParticipationViolationAction
};

Route::get('/', ListParticipationViolationsAction::class);
Route::post('/', CreateParticipationViolationAction::class);
Route::get('/{id}', GetParticipationViolationByIdAction::class);
Route::put('/{id}', UpdateParticipationViolationAction::class);
Route::delete('/{id}', DeleteParticipationViolationAction::class);
