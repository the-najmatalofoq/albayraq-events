<?php
// modules/EventShift/Presentation/Http/Action/ShowShiftAction.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowShiftAction
{
    public function __construct(
        private EventShiftRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $shift = $this->repository->findById(ShiftId::fromString($id));

        if ($shift === null || $shift->eventId->value !== $eventId) {
            return $this->responder->notFound('messages.shift.not_found');
        }

        return $this->responder->success([
            'id' => $shift->uuid->value,
            'event_id' => $shift->eventId->value,
            'position_id' => $shift->positionId->value,
            'label' => $shift->label,
            'start_at' => $shift->startAt->format(\DateTimeInterface::ATOM),
            'end_at' => $shift->endAt->format(\DateTimeInterface::ATOM),
            'max_assignees' => $shift->maxAssignees,
            'status' => $shift->status->value,
        ]);
    }
}
