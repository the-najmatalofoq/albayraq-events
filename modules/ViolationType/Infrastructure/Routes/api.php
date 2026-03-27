<?php
// modules/ViolationType/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ViolationType\Presentation\Http\Action\ListViolationTypesAction;

Route::get('/', ListViolationTypesAction::class);
