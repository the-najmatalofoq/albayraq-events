<?php
// modules/ViolationType/Presentation/Http/Action/CreateViolationTypeAction.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action;

use Modules\ViolationType\Application\Command\CreateViolationType\CreateViolationTypeCommand;
use Modules\ViolationType\Application\Command\CreateViolationType\CreateViolationTypeHandler;
use Modules\ViolationType\Presentation\Http\Request\CreateViolationTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class CreateViolationTypeAction
{
    public function __construct(
        private CreateViolationTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(CreateViolationTypeRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateViolationTypeCommand(
            name: $request->validated('name'),
            deductionAmount: $request->validated('deduction_amount') !== null ? (float) $request->validated('deduction_amount') : null,
            deductionCurrency: $request->validated('deduction_currency'),
            severity: $request->validated('severity'),
            eventId: $request->validated('event_id'),
            isActive: $request->validated('is_active', true)
        ));

        return $this->responder->created(['id' => $id->value]);
    }
}
