<?php
// modules/EmployeeAnswer/Domain/EmployeeAnswer.php
declare(strict_types=1);

namespace Modules\EmployeeAnswer\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EmployeeQuizAttempt\Domain\ValueObject\AttemptId;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\EmployeeAnswer\Domain\ValueObject\AnswerId;

final class EmployeeAnswer extends AggregateRoot
{
    private function __construct(
        public readonly AnswerId $uuid,
        public readonly AttemptId $attemptId,
        public readonly QuestionId $questionId,
        public readonly string $answerId,
        public readonly bool $isCorrect,
    ) {}

    public static function create(
        AnswerId $uuid,
        AttemptId $attemptId,
        QuestionId $questionId,
        string $answerId,
        bool $isCorrect,
    ): self {
        return new self($uuid, $attemptId, $questionId, $answerId, $isCorrect);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
