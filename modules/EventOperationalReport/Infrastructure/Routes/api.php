<?php
// modules/EventOperationalReport/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventOperationalReport\Presentation\Http\Action\ListEventOperationalReportsAction;

Route::get('/', ListEventOperationalReportsAction::class);
