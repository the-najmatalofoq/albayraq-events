<?php
// modules/EventTask/Infrastructure/Persistence/Eloquent/EloquentEventTaskRepository.php
declare(strict_types=1);

namespace Modules\EventTask\Infrastructure\Persistence\Eloquent;

use Modules\EventTask\Domain\EventTask;
use Modules\EventTask\Domain\ValueObject\TaskId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventTask\Domain\Repository\EventTaskRepositoryInterface;
use Modules\EventTask\Infrastructure\Persistence\EventTaskReflector;

final class EloquentEventTaskRepository implements EventTaskRepositoryInterface
{
    public function nextIdentity(): TaskId
    {
        return TaskId::generate();
    }

    public function save(EventTask $task): void
    {
        EventTaskModel::updateOrCreate(
            ['id' => $task->uuid->value],
            [
                'event_id' => $task->eventId->value,
                'group_id' => $task->groupId?->value,
                'assigned_to' => $task->assignedTo?->value,
                'title' => $task->title->toArray(),
                'description' => $task->description?->toArray(),
                'status' => $task->status->value,
                'due_at' => $task->dueAt?->format('Y-m-d H:i:s'),
                'created_by' => $task->createdBy->value,
            ]
        );
    }

    public function findById(TaskId $id): ?EventTask
    {
        $model = EventTaskModel::find($id->value);
        return $model ? EventTaskReflector::fromModel($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventTaskModel::where('event_id', $eventId->value)
            ->get()
            ->map(function (EventTaskModel $model) {
                return EventTaskReflector::fromModel($model);
            })
            ->toArray();
    }
}
