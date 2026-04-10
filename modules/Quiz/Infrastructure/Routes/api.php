<?php
// modules/Quiz/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Quiz\Presentation\Http\Action\CreateQuizAction;
use Modules\Quiz\Presentation\Http\Action\ListQuizzesAction;
use Modules\Quiz\Presentation\Http\Action\ShowQuizAction;
use Modules\Quiz\Presentation\Http\Action\UpdateQuizAction;
use Modules\Quiz\Presentation\Http\Action\DeleteQuizAction;

Route::prefix('{eventId}/quizzes')->group(function () {
    Route::post('/', CreateQuizAction::class);
    Route::get('/', ListQuizzesAction::class);
    Route::get('/{id}', ShowQuizAction::class);
    Route::patch('/{id}', UpdateQuizAction::class);
    Route::delete('/{id}', DeleteQuizAction::class);
});
