<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmSoftDeleteQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Crm\CrmSoftDeleteQuestionHandler;
use Modules\Question\Application\Commands\Crm\CrmSoftDeleteQuestionCommand;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmSoftDeleteQuestionAction
{
    public function __construct(
        private CrmSoftDeleteQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new CrmSoftDeleteQuestionCommand($id));

        return $this->responder->noContent();
    }
}
