<?php
// modules/EventStaffingGroup/Domain/EventStaffingGroup.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Domain;

use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\User\Domain\ValueObject\UserId;

final class EventStaffingGroup extends AggregateRoot
{
    private function __construct(
        public readonly GroupId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $name,
        public private(set) HexColor $color,
        public private(set) bool $isLocked = false,
        public private(set) bool $isActive = true,
        public private(set) ?UserId $leaderId = null,
    ) {
    }

    public static function create(
        GroupId $uuid,
        EventId $eventId,
        TranslatableText $name,
        HexColor $color,
        bool $isActive = true,
        bool $isLocked = false,
        ?UserId $leaderId = null
    ): self {
        return new self($uuid, $eventId, $name, $color, $isLocked, $isActive, $leaderId);
    }

    public static function reconstitute(
        GroupId $uuid,
        EventId $eventId,
        TranslatableText $name,
        HexColor $color,
        bool $isActive,
        bool $isLocked,
        ?UserId $leaderId = null
    ): self {
        return new self($uuid, $eventId, $name, $color, $isLocked, $isActive, $leaderId);
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
