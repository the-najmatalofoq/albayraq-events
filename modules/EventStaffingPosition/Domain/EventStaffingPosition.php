<?php
// modules/EventStaffingPosition/Domain/EventStaffingPosition.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final class EventStaffingPosition extends AggregateRoot
{
    private function __construct(
        public readonly PositionId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $title,
        public private(set) TranslatableText $requirements,
        public private(set) int $headcount,
        public private(set) ?Money $wage,
        public private(set) bool $isActive,
    ) {
    }

    public static function create(
        PositionId $uuid,
        EventId $eventId,
        TranslatableText $title,
        TranslatableText $requirements,
        int $headcount,
        ?Money $wage = null,
        bool $isActive = true
    ): self {
        return new self($uuid, $eventId, $title, $requirements, $headcount, $wage, $isActive);
    }

    public function update(
        TranslatableText $title,
        TranslatableText $requirements,
        int $headcount,
        ?Money $wage = null
    ): void {
        $this->title = $title;
        $this->requirements = $requirements;
        $this->headcount = $headcount;
        $this->wage = $wage;
    }

    public function activate(): void
    {
        $this->isActive = true;
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
