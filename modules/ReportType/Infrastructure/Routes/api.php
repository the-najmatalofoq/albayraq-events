<?php
// modules/ReportType/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ReportType\Presentation\Http\Action\ListReportTypesAction;

Route::get('/', ListReportTypesAction::class);
