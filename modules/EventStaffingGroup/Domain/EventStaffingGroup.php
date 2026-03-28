<?php
// modules/EventStaffingGroup/Domain/EventStaffingGroup.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;

final class EventStaffingGroup extends AggregateRoot
{
    private function __construct(
        public readonly GroupId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $name,
        public private(set) HexColor $color,
        public private(set) bool $isLocked = false,
        public private(set) bool $isActive = true,
    ) {}

    public static function create(
        GroupId $uuid,
        EventId $eventId,
        TranslatableText $name,
        HexColor $color,
        bool $isActive = true,
        bool $isLocked = false
    ): self {
        return new self($uuid, $eventId, $name, $color, $isLocked, $isActive);
    }

    public function update(
        TranslatableText $name,
        HexColor $color
    ): void {
        if ($this->isLocked) {
            throw new \DomainException("Cannot update a locked staffing group.");
        }
        $this->name = $name;
        $this->color = $color;
    }

    public function lock(): void
    {
        $this->isLocked = true;
    }

    public function unlock(): void
    {
        $this->isLocked = false;
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
