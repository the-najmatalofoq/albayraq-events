<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\UpdateNationalityRequest;
use Modules\Geography\Application\Command\UpdateNationality\UpdateNationalityCommand;
use Modules\Geography\Application\Command\UpdateNationality\UpdateNationalityHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class UpdateNationalityAction
{
    public function __construct(
        private readonly UpdateNationalityHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(UpdateNationalityRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateNationalityCommand($id, $request->validated()));
        return $this->responder->success(message: 'Nationality updated successfully.');
    }
}
