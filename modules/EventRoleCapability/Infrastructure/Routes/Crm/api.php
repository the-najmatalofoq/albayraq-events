<?php
// modules/EventRoleCapability/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmListEventRoleCapabilitiesAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmListPaginatedEventRoleCapabilitiesAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmShowEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmCreateEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmUpdateEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmSoftDeleteEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmHardDeleteEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Crm\CrmRestoreEventRoleCapabilityAction;

Route::get('/', CrmListEventRoleCapabilitiesAction::class);
Route::get('/paginated', CrmListPaginatedEventRoleCapabilitiesAction::class);
Route::get('/{id}', CrmShowEventRoleCapabilityAction::class);
Route::post('/', CrmCreateEventRoleCapabilityAction::class);
Route::put('/{id}', CrmUpdateEventRoleCapabilityAction::class);
Route::delete('/{id}', CrmSoftDeleteEventRoleCapabilityAction::class);
Route::delete('/{id}/hard', CrmHardDeleteEventRoleCapabilityAction::class);
Route::post('/{id}/restore', CrmRestoreEventRoleCapabilityAction::class);
