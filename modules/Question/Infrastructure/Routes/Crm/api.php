<?php
// filePath: modules/Question/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Question\Presentation\Http\Action\Crm\CrmListQuestionsAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmListPaginatedQuestionsAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmShowQuestionAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmCreateQuestionAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmUpdateQuestionAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmSoftDeleteQuestionAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmHardDeleteQuestionAction;
use Modules\Question\Presentation\Http\Action\Crm\CrmRestoreQuestionAction;

Route::get('/', CrmListQuestionsAction::class);
Route::get('/paginated', CrmListPaginatedQuestionsAction::class);
Route::get('/{id}', CrmShowQuestionAction::class);
Route::post('/', CrmCreateQuestionAction::class);
Route::put('/{id}', CrmUpdateQuestionAction::class);
Route::delete('/{id}', CrmSoftDeleteQuestionAction::class);
Route::delete('/{id}/hard', CrmHardDeleteQuestionAction::class);
Route::post('/{id}/restore', CrmRestoreQuestionAction::class);
