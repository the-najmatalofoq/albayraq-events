<?php
// modules/ParticipationViolation/Presentation/Http/Action/GetParticipationViolationByIdAction.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\ParticipationViolation\Application\Query\GetParticipationViolation\GetParticipationViolationQuery;
use Modules\ParticipationViolation\Application\Query\GetParticipationViolation\GetParticipationViolationHandler;
use Modules\ParticipationViolation\Presentation\Http\Presenter\ParticipationViolationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class GetParticipationViolationByIdAction
{
    public function __construct(
        private GetParticipationViolationHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $violation = $this->handler->handle(new GetParticipationViolationQuery($id));

        if (!$violation) {
            return $this->responder->notFound(messageKey: 'messages.not_found');
        }

        return $this->responder->success(
            data: ParticipationViolationPresenter::fromDomain($violation)
        );
    }
}
