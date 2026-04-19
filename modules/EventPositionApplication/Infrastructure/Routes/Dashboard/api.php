<?php
// filePath: modules/EventPositionApplication/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\ListEventPositionApplicationsAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\ListPaginatedEventPositionApplicationsAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\ShowEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\CreateEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\UpdateEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\SoftDeleteEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\HardDeleteEventPositionApplicationAction;
use Modules\EventPositionApplication\Presentation\Http\Action\Dashboard\RestoreEventPositionApplicationAction;

Route::get('/', ListEventPositionApplicationsAction::class);
Route::get('/paginated', ListPaginatedEventPositionApplicationsAction::class);
Route::get('/{id}', ShowEventPositionApplicationAction::class);
Route::post('/', CreateEventPositionApplicationAction::class);
Route::put('/{id}', UpdateEventPositionApplicationAction::class);
Route::delete('/{id}', SoftDeleteEventPositionApplicationAction::class);
Route::delete('/{id}/hard', HardDeleteEventPositionApplicationAction::class);
Route::post('/{id}/restore', RestoreEventPositionApplicationAction::class);
