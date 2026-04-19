<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\ViolationType\Application\Command\UpdateViolationType\UpdateViolationTypeCommand;
use Modules\ViolationType\Application\Command\UpdateViolationType\UpdateViolationTypeHandler;
use Modules\ViolationType\Presentation\Http\Request\Dashboard\UpdateViolationTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateViolationTypeAction
{
    public function __construct(
        private UpdateViolationTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(UpdateViolationTypeRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateViolationTypeCommand(
            id: $id,
            name: TranslatableText::fromArray($request->validated('name') ?? []),
            slug: $request->validated('slug'),
            isActive: $request->has('is_active') ? $request->validated('is_active') : null
        ));

        return $this->responder->success(
            messageKey: 'messages.updated'
        );
    }
}
