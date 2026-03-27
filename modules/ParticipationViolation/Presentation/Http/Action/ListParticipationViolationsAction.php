<?php
// modules/ParticipationViolation/Presentation/Http/Action/ListParticipationViolationsAction.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationViolation\Presentation\Http\Presenter\ParticipationViolationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListParticipationViolationsAction
{
    public function __construct(
        private ParticipationViolationRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $participationId = $request->query('participation_id');
        
        if (!$participationId) {
            return $this->responder->error('MISSING_PARTICIPATION_ID', 400, 'Participation ID is required');
        }

        $violations = $this->repository->findByParticipationId(ParticipationId::fromString($participationId));

        return $this->responder->success(
            data: array_map(fn($v) => ParticipationViolationPresenter::fromDomain($v), $violations)
        );
    }
}
