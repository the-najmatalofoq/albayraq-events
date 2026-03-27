<?php
// modules/EventPositionApplication/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventPositionApplication\Presentation\Http\Action\ListEventPositionApplicationsAction;

Route::get('/', ListEventPositionApplicationsAction::class);
