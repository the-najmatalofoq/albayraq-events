<?php
// modules/ParticipationEvaluation/Presentation/Http/Action/ListParticipationEvaluationsAction.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\ParticipationEvaluation\Domain\Repository\ParticipationEvaluationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationEvaluation\Presentation\Http\Presenter\ParticipationEvaluationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListParticipationEvaluationsAction
{
    public function __construct(
        private ParticipationEvaluationRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $participationId = $request->query('participation_id');
        
        if (!$participationId) {
            return $this->responder->error('MISSING_PARTICIPATION_ID', 400, 'Participation ID is required');
        }

        $evaluation = $this->repository->findByParticipationId(ParticipationId::fromString($participationId));

        if (!$evaluation) {
            return $this->responder->success(data: null);
        }

        return $this->responder->success(
            data: ParticipationEvaluationPresenter::fromDomain($evaluation)
        );
    }
}
