<?php
// modules/EventParticipation/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventParticipation\Presentation\Http\Action\ListEventParticipationsAction;

Route::get('/', ListEventParticipationsAction::class);
