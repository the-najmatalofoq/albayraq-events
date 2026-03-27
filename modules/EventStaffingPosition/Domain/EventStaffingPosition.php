<?php
// modules/EventStaffingPosition/Domain/EventStaffingPosition.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final class EventStaffingPosition extends AggregateRoot
{
    public function __construct(
        public readonly PositionId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $title,
        public private(set) TranslatableText $requirements,
        public private(set) int $quantity,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        PositionId $uuid,
        EventId $eventId,
        TranslatableText $title,
        TranslatableText $requirements,
        int $quantity,
        bool $isActive = true
    ): self {
        return new self($uuid, $eventId, $title, $requirements, $quantity, $isActive);
    }

    public function update(
        TranslatableText $title,
        TranslatableText $requirements,
        int $quantity
    ): void {
        $this->title = $title;
        $this->requirements = $requirements;
        $this->quantity = $quantity;
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
