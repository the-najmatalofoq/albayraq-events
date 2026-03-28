<?php
// modules/ParticipationEvaluation/Domain/ParticipationEvaluation.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationEvaluation\Domain\ValueObject\EvaluationId;
use Modules\User\Domain\ValueObject\UserId;

final class ParticipationEvaluation extends AggregateRoot
{
    private function __construct(
        public readonly EvaluationId $uuid,
        public readonly ParticipationId $participationId,
        public readonly UserId $evaluatorId,
        public private(set) DateTimeImmutable $date,
        public private(set) float $score,
        public private(set) ?string $notes,
        public private(set) bool $isLocked,
        public private(set) ?DateTimeImmutable $lockedAt,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        EvaluationId $uuid,
        ParticipationId $participationId,
        UserId $evaluatorId,
        DateTimeImmutable $date,
        float $score,
        ?string $notes = null
    ): self {
        return new self(
            uuid: $uuid,
            participationId: $participationId,
            evaluatorId: $evaluatorId,
            date: $date,
            score: $score,
            notes: $notes,
            isLocked: false,
            lockedAt: null,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function lock(): void
    {
        $this->isLocked = true;
        $this->lockedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
