<?php
// modules\User\Infrastructure\Routes\MedicalRecord\api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Presentation\Http\Action\MedicalRecord\UpdateMedicalReportAction;

Route::put('/medical', UpdateMedicalReportAction::class);
