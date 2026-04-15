<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ViolationType\Application\Command\CreateViolationType\CreateViolationTypeCommand;
use Modules\ViolationType\Application\Command\CreateViolationType\CreateViolationTypeHandler;
use Modules\ViolationType\Presentation\Http\Request\Dashboard\StoreViolationTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateViolationTypeAction
{
    public function __construct(
        private CreateViolationTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(StoreViolationTypeRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateViolationTypeCommand(
            name: TranslatableText::fromArray($request->validated('name')),
            slug: $request->validated('slug'),
            isActive: $request->validated('is_active', true)
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.created'
        );
    }
}
