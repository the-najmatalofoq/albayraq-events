<?php
// modules/EventShift/Presentation/Http/Action/ListShiftsAction.php
declare(strict_types=1);

namespace Modules\EventShift\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Presentation\Http\Presenter\EventShiftPresenter;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListShiftsAction
{
    public function __construct(
        private EventShiftRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $positionId = $request->query('position_id');

        $shifts = is_string($positionId)
            ? $this->repository->findByEventAndPosition(EventId::fromString($eventId), PositionId::fromString($positionId))
            : $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn ($s) => EventShiftPresenter::fromDomain($s), $shifts),
        );
    }
}
