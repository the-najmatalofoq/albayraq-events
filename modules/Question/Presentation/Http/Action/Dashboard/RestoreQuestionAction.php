<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmRestoreQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardRestoreQuestionHandler;
use Modules\Question\Application\Commands\Dashboard\DashboardRestoreQuestionCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class RestoreQuestionAction
{
    public function __construct(
        private RestoreQuestionHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmRestoreQuestionCommand($id));

        // fix: we must build a (open key): for the translation of each module, like for the (question) module, we must make it like question::messages.question_restored
        return $this->responder->success(messageKey: 'messages.question.restored');
    }
}
