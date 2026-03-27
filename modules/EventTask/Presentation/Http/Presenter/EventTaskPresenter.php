<?php
// modules/EventTask/Presentation/Http/Presenter/EventTaskPresenter.php
declare(strict_types=1);

namespace Modules\EventTask\Presentation\Http\Presenter;

use Modules\EventTask\Domain\EventTask;

final class EventTaskPresenter
{
    public static function fromDomain(EventTask $task): array
    {
        return [
            'id' => $task->uuid->value,
            'event_id' => $task->eventId->value,
            'group_id' => $task->groupId?->value,
            'assigned_to' => $task->assignedTo?->value,
            'title' => $task->title->toArray(),
            'description' => $task->description?->toArray(),
            'status' => $task->status->value,
            'status_label' => $task->status->label(),
            'due_at' => $task->dueAt?->format('Y-m-d H:i:s'),
            'created_by' => $task->createdBy->value,
        ];
    }
}
