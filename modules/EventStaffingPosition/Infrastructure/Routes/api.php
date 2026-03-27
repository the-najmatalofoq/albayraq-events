<?php
// modules/EventStaffingPosition/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventStaffingPosition\Presentation\Http\Action\ListEventStaffingPositionsAction;

Route::get('/', ListEventStaffingPositionsAction::class);
