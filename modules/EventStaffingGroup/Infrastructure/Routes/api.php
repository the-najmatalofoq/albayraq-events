<?php
// modules/EventStaffingGroup/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventStaffingGroup\Presentation\Http\Action\ListEventStaffingGroupsAction;

Route::get('/', ListEventStaffingGroupsAction::class);
