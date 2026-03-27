<?php
// modules/Event/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Event\Presentation\Http\Action\ListEventsAction;

Route::get('/', ListEventsAction::class);
