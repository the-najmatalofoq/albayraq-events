<?php
// modules/ParticipationViolation/Presentation/Http/Action/UpdateParticipationViolationAction.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\ParticipationViolation\Application\Command\UpdateParticipationViolation\UpdateParticipationViolationCommand;
use Modules\ParticipationViolation\Application\Command\UpdateParticipationViolation\UpdateParticipationViolationHandler;
use Modules\ParticipationViolation\Presentation\Http\Request\ParticipationViolationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use DateTimeImmutable;

final readonly class UpdateParticipationViolationAction
{
    public function __construct(
        private UpdateParticipationViolationHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(ParticipationViolationRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateParticipationViolationCommand(
            id: $id,
            violationTypeId: $request->validated('violation_type_id'),
            date: new DateTimeImmutable($request->validated('date')),
            deductionTypeId: $request->validated('deduction_type_id'),
            penaltyTypeId: $request->validated('penalty_type_id'),
            description: $request->validated('description'),
        ));

        return $this->responder->success(
            messageKey: 'messages.updated'
        );
    }
}
