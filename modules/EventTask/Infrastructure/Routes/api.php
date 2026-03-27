<?php
// modules/EventTask/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventTask\Presentation\Http\Action\ListEventTasksAction;

Route::get('/', ListEventTasksAction::class);
