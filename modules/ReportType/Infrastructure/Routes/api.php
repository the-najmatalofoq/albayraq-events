<?php
// modules/ReportType/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ReportType\Presentation\Http\Action\CreateReportTypeAction;
use Modules\ReportType\Presentation\Http\Action\UpdateReportTypeAction;
use Modules\ReportType\Presentation\Http\Action\DeleteReportTypeAction;
use Modules\ReportType\Presentation\Http\Action\GetReportTypeAction;
use Modules\ReportType\Presentation\Http\Action\ListReportTypesPaginationAction;

Route::get('/pagination', ListReportTypesPaginationAction::class);
Route::post('/', CreateReportTypeAction::class);
Route::get('/{id}', GetReportTypeAction::class);
Route::put('/{id}', UpdateReportTypeAction::class);
Route::delete('/{id}', DeleteReportTypeAction::class);
