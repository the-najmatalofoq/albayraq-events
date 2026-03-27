<?php
// modules/EventStaffingPosition/Presentation/Http/Action/ListEventStaffingPositionsAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Presentation\Http\Presenter\EventStaffingPositionPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventStaffingPositionsAction
{
    public function __construct(
        private EventStaffingPositionRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $eventId = $request->query('event_id');
        
        if (!$eventId) {
            return $this->responder->error('MISSING_EVENT_ID', 400, 'Event ID is required');
        }

        $positions = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($p) => EventStaffingPositionPresenter::fromDomain($p), $positions)
        );
    }
}
