<?php
// modules/ParticipationViolation/Presentation/Http/Action/CreateParticipationViolationAction.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\ParticipationViolation\Application\Command\CreateParticipationViolation\CreateParticipationViolationCommand;
use Modules\ParticipationViolation\Application\Command\CreateParticipationViolation\CreateParticipationViolationHandler;
use Modules\ParticipationViolation\Presentation\Http\Request\ParticipationViolationRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use DateTimeImmutable;

final readonly class CreateParticipationViolationAction
{
    public function __construct(
        private CreateParticipationViolationHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(ParticipationViolationRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateParticipationViolationCommand(
            participationId: $request->validated('event_participation_id'),
            violationTypeId: $request->validated('violation_type_id'),
            reportedBy: $request->validated('reported_by'),
            date: new DateTimeImmutable($request->validated('date')),
            deductionTypeId: $request->validated('deduction_type_id'),
            penaltyTypeId: $request->validated('penalty_type_id'),
            description: $request->validated('description'),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.created'
        );
    }
}
