<?php
// modules/EventStaffingGroup/Presentation/Http/Action/DeleteEventStaffingGroupAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteEventStaffingGroupAction
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->repository->delete(GroupId::fromString($id));
        return $this->responder->noContent();
    }
}
