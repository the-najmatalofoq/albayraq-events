<?php
// modules/ParticipationEvaluation/Domain/ParticipationEvaluation.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationEvaluation\Domain\ValueObject\EvaluationId;
use Modules\IAM\Domain\ValueObject\UserId;

final class ParticipationEvaluation extends AggregateRoot
{
    public function __construct(
        public readonly EvaluationId $uuid,
        public readonly ParticipationId $participationId,
        public private(set) int $rating,
        public private(set) ?TranslatableText $feedback = null,
        public readonly UserId $evaluatedBy,
        public readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5');
        }
    }

    public static function create(
        EvaluationId $uuid,
        ParticipationId $participationId,
        int $rating,
        UserId $evaluatedBy,
        ?TranslatableText $feedback = null
    ): self {
        return new self($uuid, $participationId, $rating, $feedback, $evaluatedBy);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
