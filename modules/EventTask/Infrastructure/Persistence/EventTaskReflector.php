<?php
// modules/EventTask/Infrastructure/Persistence/EventTaskReflector.php
declare(strict_types=1);

namespace Modules\EventTask\Infrastructure\Persistence;

use Modules\EventTask\Domain\EventTask;
use Modules\EventTask\Domain\ValueObject\TaskId;
use Modules\EventTask\Domain\ValueObject\TaskStatus;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventTask\Infrastructure\Persistence\Eloquent\EventTaskModel;

final class EventTaskReflector
{
    public static function fromModel(EventTaskModel $model): EventTask
    {
        $reflection = new \ReflectionClass(EventTask::class);
        $task = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => TaskId::fromString($model->id),
            'eventId' => EventId::fromString($model->event_id),
            'title' => TranslatableText::fromArray($model->title),
            'status' => TaskStatus::from($model->status),
            'description' => $model->description ? TranslatableText::fromArray($model->description) : null,
            'groupId' => $model->group_id ? GroupId::fromString($model->group_id) : null,
            'assignedTo' => $model->assigned_to ? UserId::fromString($model->assigned_to) : null,
            'createdBy' => UserId::fromString($model->created_by),
            'dueAt' => $model->due_at ? \DateTimeImmutable::createFromMutable($model->due_at) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($task, $value);
        }

        return $task;
    }
}
