<?php
// filePath: modules/Question/Infrastructure/Routes/Crm/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Question\Presentation\Http\Action\Dashboard\ListQuestionsAction;
use Modules\Question\Presentation\Http\Action\Dashboard\ListPaginatedQuestionsAction;
use Modules\Question\Presentation\Http\Action\Dashboard\ShowQuestionAction;
use Modules\Question\Presentation\Http\Action\Dashboard\CreateQuestionAction;
use Modules\Question\Presentation\Http\Action\Dashboard\UpdateQuestionAction;
use Modules\Question\Presentation\Http\Action\Dashboard\SoftDeleteQuestionAction;
use Modules\Question\Presentation\Http\Action\Dashboard\HardDeleteQuestionAction;
use Modules\Question\Presentation\Http\Action\Dashboard\RestoreQuestionAction;

Route::get('/', ListQuestionsAction::class);
Route::get('/paginated', ListPaginatedQuestionsAction::class);
Route::get('/{id}', ShowQuestionAction::class);
Route::post('/', CreateQuestionAction::class);
Route::put('/{id}', UpdateQuestionAction::class);
Route::delete('/{id}', SoftDeleteQuestionAction::class);
Route::delete('/{id}/hard', HardDeleteQuestionAction::class);
Route::post('/{id}/restore', RestoreQuestionAction::class);
