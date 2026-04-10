<?php
// modules/EventStaffingGroup/Presentation/Http/Action/ListEventStaffingGroupsAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventStaffingGroupsAction
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId): JsonResponse
    {
        $groups = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(function ($g) {
                return [
                    'id' => $g->uuid->value,
                    'name' => $g->name->toArray(),
                    'color' => $g->color,
                    'is_locked' => $g->isLocked,
                ];
            }, $groups)
        );
    }
}
