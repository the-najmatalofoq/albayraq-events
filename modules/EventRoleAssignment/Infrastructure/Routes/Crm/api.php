<?php
// modules/EventRoleAssignment/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventRoleAssignment\Presentation\Http\Action\Crm\CrmListEventRoleAssignmentsAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Crm\CrmCreateEventRoleAssignmentAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Crm\CrmShowEventRoleAssignmentAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Crm\CrmUpdateEventRoleAssignmentAction;
use Modules\EventRoleAssignment\Presentation\Http\Action\Crm\CrmDeleteEventRoleAssignmentAction;

Route::get('/', CrmListEventRoleAssignmentsAction::class);
Route::post('/', CrmCreateEventRoleAssignmentAction::class);
Route::get('/{id}', CrmShowEventRoleAssignmentAction::class);
Route::put('/{id}', CrmUpdateEventRoleAssignmentAction::class);
Route::delete('/{id}', CrmDeleteEventRoleAssignmentAction::class);
