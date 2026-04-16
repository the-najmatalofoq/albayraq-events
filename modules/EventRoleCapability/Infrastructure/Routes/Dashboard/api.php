<?php
// modules/EventRoleCapability/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\ListEventRoleCapabilitiesAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\ListPaginatedEventRoleCapabilitiesAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\ShowEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\CreateEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\UpdateEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\SoftDeleteEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\HardDeleteEventRoleCapabilityAction;
use Modules\EventRoleCapability\Presentation\Http\Action\Dashboard\RestoreEventRoleCapabilityAction;

Route::get('/', ListEventRoleCapabilitiesAction::class);
Route::get('/paginated', ListPaginatedEventRoleCapabilitiesAction::class);
Route::get('/{id}', ShowEventRoleCapabilityAction::class);
Route::post('/', CreateEventRoleCapabilityAction::class);
Route::put('/{id}', UpdateEventRoleCapabilityAction::class);
Route::delete('/{id}', SoftDeleteEventRoleCapabilityAction::class);
Route::delete('/{id}/hard', HardDeleteEventRoleCapabilityAction::class);
Route::post('/{id}/restore', RestoreEventRoleCapabilityAction::class);
