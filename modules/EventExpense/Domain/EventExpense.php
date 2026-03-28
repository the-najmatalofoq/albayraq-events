<?php
// modules/EventExpense/Domain/EventExpense.php
declare(strict_types=1);

namespace Modules\EventExpense\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventExpense\Domain\Enum\ExpenseStatusEnum;
use Modules\EventExpense\Domain\ValueObject\ExpenseId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\User\Domain\ValueObject\UserId;

final class EventExpense extends AggregateRoot
{
    private function __construct(
        public readonly ExpenseId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $description,
        public private(set) float $amount,
        public private(set) ?string $category,
        public private(set) ExpenseStatusEnum $status,
        public readonly UserId $submittedBy,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?UserId $approvedBy = null,
        public private(set) ?DateTimeImmutable $approvedAt = null,
    ) {}

    public static function create(
        ExpenseId $uuid,
        EventId $eventId,
        TranslatableText $description,
        float $amount,
        UserId $submittedBy,
        ?string $category = null,
    ): self {
        return new self(
            uuid: $uuid,
            eventId: $eventId,
            description: $description,
            amount: $amount,
            category: $category,
            status: ExpenseStatusEnum::PENDING,
            submittedBy: $submittedBy,
            createdAt: new DateTimeImmutable(),
        );
    }

    public function approve(UserId $approverId): void
    {
        $this->status = ExpenseStatusEnum::APPROVED;
        $this->approvedBy = $approverId;
        $this->approvedAt = new DateTimeImmutable();
    }

    public function reject(): void
    {
        $this->status = ExpenseStatusEnum::REJECTED;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
