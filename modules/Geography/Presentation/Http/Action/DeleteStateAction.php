<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Geography\Application\Command\DeleteState\DeleteStateCommand;
use Modules\Geography\Application\Command\DeleteState\DeleteStateHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class DeleteStateAction
{
    public function __construct(
        private readonly DeleteStateHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->handler->handle(new DeleteStateCommand($id));
        return $this->responder->success(message: 'State deleted successfully.');
    }
}
