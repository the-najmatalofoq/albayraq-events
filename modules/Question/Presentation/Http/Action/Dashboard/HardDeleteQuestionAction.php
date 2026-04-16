<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmHardDeleteQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardHardDeleteQuestionHandler;
use Modules\Question\Application\Commands\Dashboard\DashboardHardDeleteQuestionCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class HardDeleteQuestionAction
{
    public function __construct(
        private HardDeleteQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmHardDeleteQuestionCommand($id));

        return $this->responder->noContent();
    }
}
