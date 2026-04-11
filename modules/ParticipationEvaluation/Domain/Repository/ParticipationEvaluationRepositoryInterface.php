<?php
// modules/ParticipationEvaluation/Domain/Repository/ParticipationEvaluationRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Domain\Repository;

use Modules\ParticipationEvaluation\Domain\ParticipationEvaluation;
use Modules\ParticipationEvaluation\Domain\ValueObject\EvaluationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface ParticipationEvaluationRepositoryInterface
{
    public function nextIdentity(): EvaluationId;

    public function save(ParticipationEvaluation $evaluation): void;

    public function findById(EvaluationId $id): ?ParticipationEvaluation;

    public function findByParticipationId(ParticipationId $participationId): ?ParticipationEvaluation;
}
