<?php
// modules/EventStaffingGroup/Presentation/Http/Action/ShowEventStaffingGroupAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowEventStaffingGroupAction
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $group = $this->repository->findById(GroupId::fromString($id));

        if ($group === null) {
            return $this->responder->error(errorCode: 'NOT_FOUND', status: 404);
        }

        return $this->responder->success(data: [
            'id' => $group->uuid->value,
            'name' => $group->name->toArray(),
            'color' => $group->color,
            'is_locked' => $group->isLocked,
        ]);
    }
}
