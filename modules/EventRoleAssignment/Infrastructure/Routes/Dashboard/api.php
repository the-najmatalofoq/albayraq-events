<?php
// modules/EventRoleAssignment/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard\ListEventRoleAssignmentsAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard\CreateEventRoleAssignmentAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard\ShowEventRoleAssignmentAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard\UpdateEventRoleAssignmentAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Dashboard\DeleteEventRoleAssignmentAction;

Route::get('/', ListEventRoleAssignmentsAction::class);
Route::post('/', CreateEventRoleAssignmentAction::class);
Route::get('/{id}', ShowEventRoleAssignmentAction::class);
Route::put('/{id}', UpdateEventRoleAssignmentAction::class);
Route::delete('/{id}', DeleteEventRoleAssignmentAction::class);
