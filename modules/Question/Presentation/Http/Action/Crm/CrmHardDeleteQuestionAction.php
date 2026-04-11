<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmHardDeleteQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Crm\CrmHardDeleteQuestionHandler;
use Modules\Question\Application\Commands\Crm\CrmHardDeleteQuestionCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmHardDeleteQuestionAction
{
    public function __construct(
        private CrmHardDeleteQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmHardDeleteQuestionCommand($id));

        return $this->responder->noContent();
    }
}
