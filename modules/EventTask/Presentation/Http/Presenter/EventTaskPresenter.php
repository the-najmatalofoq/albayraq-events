<?php
// modules/EventTask/Presentation/Http/Presenter/EventTaskPresenter.php
declare(strict_types=1);

namespace Modules\EventTask\Presentation\Http\Presenter;

use Modules\EventTask\Domain\EventTask;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\User\Domain\User;

final class EventTaskPresenter
{
    public function present(EventTask $task, ?User $assignee = null): array
    {
        return [
            'uuid'          => $task->uuid->value,
            'event_id'       => $task->eventId->value,
            'title'         => $task->title->toArray(),
            'description'   => $task->description?->toArray(),
            'status'        => $task->status->value,
            'assignment'    => [
                'group_id'    => $task->groupId?->value,
                'assigned_to' => $task->assignedTo?->value,
            ],
            'due_at'        => $task->dueAt?->format(DATE_ATOM),
            'created_by'    => $task->createdBy->value,
            'assignee'      => $assignee ? UserPresenter::fromDomain($assignee) : null,
        ];
    }

    public function presentCollection(iterable $tasks): array
    {
        $data = [];
        foreach ($tasks as $task) {
            $data[] = $this->present($task);
        }
        return $data;
    }
}
