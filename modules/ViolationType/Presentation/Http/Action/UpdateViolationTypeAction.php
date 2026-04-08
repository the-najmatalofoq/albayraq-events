<?php
// modules/ViolationType/Presentation/Http/Action/UpdateViolationTypeAction.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action;

use Modules\ViolationType\Application\Command\UpdateViolationType\UpdateViolationTypeCommand;
use Modules\ViolationType\Application\Command\UpdateViolationType\UpdateViolationTypeHandler;
use Modules\ViolationType\Presentation\Http\Request\UpdateViolationTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class UpdateViolationTypeAction
{
    public function __construct(
        private UpdateViolationTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id, UpdateViolationTypeRequest $request): JsonResponse
    {
        $this->handler->handle(new UpdateViolationTypeCommand(
            id: $id,
            name: $request->validated('name'),
            deductionAmount: $request->validated('deduction_amount') !== null ? (float) $request->validated('deduction_amount') : null,
            deductionCurrency: $request->validated('deduction_currency'),
            severity: $request->validated('severity'),
            eventId: $request->validated('event_id'),
            isActive: $request->validated('is_active')
        ));

        return $this->responder->success(messageKey: 'messages.updated');
    }
}
