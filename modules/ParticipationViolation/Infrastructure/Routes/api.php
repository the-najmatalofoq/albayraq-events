<?php
// modules/ParticipationViolation/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ParticipationViolation\Presentation\Http\Action\ListParticipationViolationsAction;

Route::get('/', ListParticipationViolationsAction::class);
