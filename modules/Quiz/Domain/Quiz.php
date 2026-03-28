<?php
// modules/Quiz/Domain/Quiz.php
declare(strict_types=1);

namespace Modules\Quiz\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Quiz\Domain\ValueObject\QuizId;

final class Quiz extends AggregateRoot
{
    private function __construct(
        public readonly QuizId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $title,
        public private(set) ?TranslatableText $description,
        public private(set) int $passingScore,
        public private(set) bool $isActive,
    ) {}

    public static function create(
        QuizId $uuid,
        EventId $eventId,
        TranslatableText $title,
        ?TranslatableText $description = null,
        int $passingScore = 80,
    ): self {
        return new self($uuid, $eventId, $title, $description, $passingScore, true);
    }

    public function update(TranslatableText $title, ?TranslatableText $description, int $passingScore): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->passingScore = $passingScore;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
