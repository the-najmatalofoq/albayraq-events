<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\CreateNationalityRequest;
use Modules\Geography\Application\Command\CreateNationality\CreateNationalityCommand;
use Modules\Geography\Application\Command\CreateNationality\CreateNationalityHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class CreateNationalityAction
{
    public function __construct(
        private readonly CreateNationalityHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(CreateNationalityRequest $request): JsonResponse
    {
        $this->handler->handle(new CreateNationalityCommand($request->validated()));
        return $this->responder->success(message: 'Nationality created successfully.');
    }
}
