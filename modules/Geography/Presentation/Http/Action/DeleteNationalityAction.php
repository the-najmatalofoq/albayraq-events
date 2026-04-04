<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Geography\Application\Command\DeleteNationality\DeleteNationalityCommand;
use Modules\Geography\Application\Command\DeleteNationality\DeleteNationalityHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class DeleteNationalityAction
{
    public function __construct(
        private readonly DeleteNationalityHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->handler->handle(new DeleteNationalityCommand($id));
        return $this->responder->success(message: 'Nationality deleted successfully.');
    }
}
