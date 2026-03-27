<?php
// modules/EventStaffingGroup/Domain/EventStaffingGroup.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\HexColor;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;

final class EventStaffingGroup extends AggregateRoot
{
    public function __construct(
        public readonly GroupId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $name,
        public private(set) UserId $leaderId,
        public private(set) HexColor $color,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        GroupId $uuid,
        EventId $eventId,
        TranslatableText $name,
        UserId $leaderId,
        HexColor $color,
        bool $isActive = true
    ): self {
        return new self($uuid, $eventId, $name, $leaderId, $color, $isActive);
    }

    public function update(
        TranslatableText $name,
        UserId $leaderId,
        HexColor $color
    ): void {
        $this->name = $name;
        $this->leaderId = $leaderId;
        $this->color = $color;
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
