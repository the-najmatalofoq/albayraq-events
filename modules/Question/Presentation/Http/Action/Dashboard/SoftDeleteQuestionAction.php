<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmSoftDeleteQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardSoftDeleteQuestionHandler;
use Modules\Question\Application\Commands\Dashboard\DashboardSoftDeleteQuestionCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class SoftDeleteQuestionAction
{
    public function __construct(
        private SoftDeleteQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmSoftDeleteQuestionCommand($id));

        return $this->responder->noContent();
    }
}
