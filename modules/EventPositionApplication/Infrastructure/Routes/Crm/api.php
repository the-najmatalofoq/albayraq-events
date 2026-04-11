<?php
// filePath: modules/EventPositionApplication/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmListEventPositionApplicationsAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmListPaginatedEventPositionApplicationsAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmShowEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmCreateEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmUpdateEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmSoftDeleteEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmHardDeleteEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Crm\CrmRestoreEventPositionApplicationAction;

Route::get('/', CrmListEventPositionApplicationsAction::class);
Route::get('/paginated', CrmListPaginatedEventPositionApplicationsAction::class);
Route::get('/{id}', CrmShowEventPositionApplicationAction::class);
Route::post('/', CrmCreateEventPositionApplicationAction::class);
Route::put('/{id}', CrmUpdateEventPositionApplicationAction::class);
Route::delete('/{id}', CrmSoftDeleteEventPositionApplicationAction::class);
Route::delete('/{id}/hard', CrmHardDeleteEventPositionApplicationAction::class);
Route::post('/{id}/restore', CrmRestoreEventPositionApplicationAction::class);
