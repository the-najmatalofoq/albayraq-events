<?php
// modules/EventRoleCapability/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EventRoleCapability\Presentation\Http\Action\ListEventRoleCapabilitiesAction;

Route::get('/', ListEventRoleCapabilitiesAction::class);
