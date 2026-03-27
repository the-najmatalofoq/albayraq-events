<?php
// modules/ParticipationEvaluation/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ParticipationEvaluation\Presentation\Http\Action\ListParticipationEvaluationsAction;

Route::get('/', ListParticipationEvaluationsAction::class);
