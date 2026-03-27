<?php
// modules/EventTask/Domain/EventTask.php
declare(strict_types=1);

namespace Modules\EventTask\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventTask\Domain\ValueObject\TaskId;
use Modules\EventTask\Domain\ValueObject\TaskStatus;

final class EventTask extends AggregateRoot
{
    public function __construct(
        public readonly TaskId $uuid,
        public readonly EventId $eventId,
        public private(set) TranslatableText $title,
        public private(set) TaskStatus $status = TaskStatus::TODO,
        public private(set) ?TranslatableText $description = null,
        public private(set) ?GroupId $groupId = null,
        public private(set) ?UserId $assignedTo = null,
        public readonly UserId $createdBy,
        public private(set) ?\DateTimeImmutable $dueAt = null
    ) {
    }

    public static function create(
        TaskId $uuid,
        EventId $eventId,
        TranslatableText $title,
        UserId $createdBy,
        TaskStatus $status = TaskStatus::TODO,
        ?TranslatableText $description = null,
        ?GroupId $groupId = null,
        ?UserId $assignedTo = null,
        ?\DateTimeImmutable $dueAt = null
    ): self {
        return new self($uuid, $eventId, $title, $status, $description, $groupId, $assignedTo, $createdBy, $dueAt);
    }

    public function update(
        TranslatableText $title,
        ?TranslatableText $description = null,
        ?GroupId $groupId = null,
        ?UserId $assignedTo = null,
        ?\DateTimeImmutable $dueAt = null
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->groupId = $groupId;
        $this->assignedTo = $assignedTo;
        $this->dueAt = $dueAt;
    }

    public function changeStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
