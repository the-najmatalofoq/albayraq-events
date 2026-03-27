<?php
// modules/EventStaffingPositionRequirement/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventStaffingPositionRequirement\Presentation\Http\Action\ListEventStaffingPositionRequirementsAction;

Route::get('/', ListEventStaffingPositionRequirementsAction::class);
