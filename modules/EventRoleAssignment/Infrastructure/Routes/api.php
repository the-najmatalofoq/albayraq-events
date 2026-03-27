<?php
// modules/EventRoleAssignment/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventRoleAssignment\Presentation\Http\Action\ListEventRoleAssignmentsAction;

Route::get('/', ListEventRoleAssignmentsAction::class);
