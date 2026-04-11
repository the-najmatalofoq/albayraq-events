<?php
// modules/Question/Domain/Question.php
declare(strict_types=1);

namespace Modules\Question\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Question\Domain\ValueObject\QuestionId;

final class Question extends AggregateRoot
{
    private function __construct(
        public readonly QuestionId $uuid,
        public readonly QuizId $quizId,
        public private(set) TranslatableText $content,
        public private(set) string $type,
        public private(set) array $options,
        public private(set) int $scoreWeight,
        public private(set) ?\DateTimeImmutable $deletedAt = null,
    ) {}

    public static function create(
        QuestionId $uuid,
        QuizId $quizId,
        TranslatableText $content,
        array $options,
        string $type = 'multiple_choice',
        int $scoreWeight = 1,
    ): self {
        return new self($uuid, $quizId, $content, $type, $options, $scoreWeight);
    }

    public static function reconstitute(
        QuestionId $uuid,
        QuizId $quizId,
        TranslatableText $content,
        string $type,
        array $options,
        int $scoreWeight,
        ?\DateTimeImmutable $deletedAt = null,
    ): self {
        return new self($uuid, $quizId, $content, $type, $options, $scoreWeight, $deletedAt);
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    public function update(TranslatableText $content, array $options, string $type, int $scoreWeight): void
    {
        $this->content = $content;
        $this->options = $options;
        $this->type = $type;
        $this->scoreWeight = $scoreWeight;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
