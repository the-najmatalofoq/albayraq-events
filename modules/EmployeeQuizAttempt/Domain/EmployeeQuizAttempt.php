<?php
// modules/EmployeeQuizAttempt/Domain/EmployeeQuizAttempt.php
declare(strict_types=1);

namespace Modules\EmployeeQuizAttempt\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EmployeeQuizAttempt\Domain\ValueObject\AttemptId;

final class EmployeeQuizAttempt extends AggregateRoot
{
    private function __construct(
        public readonly AttemptId $uuid,
        public readonly QuizId $quizId,
        public readonly ParticipationId $participationId,
        public private(set) int $score,
        public private(set) string $status,
        public readonly DateTimeImmutable $startedAt,
        public private(set) ?DateTimeImmutable $completedAt,
    ) {}

    public static function start(AttemptId $uuid, QuizId $quizId, ParticipationId $participationId): self
    {
        return new self($uuid, $quizId, $participationId, 0, 'pending', new DateTimeImmutable(), null);
    }

    public function complete(int $score, bool $passed): void
    {
        $this->score = $score;
        $this->status = $passed ? 'passed' : 'failed';
        $this->completedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
