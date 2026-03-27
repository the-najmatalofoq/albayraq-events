<?php
// modules/EventAttendance/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventAttendance\Presentation\Http\Action\ListEventAttendanceAction;

Route::get('/', ListEventAttendanceAction::class);
