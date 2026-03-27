<?php
// modules/EventStaffingGroup/Presentation/Http/Action/ListEventStaffingGroupsAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingGroup\Presentation\Http\Presenter\EventStaffingGroupPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventStaffingGroupsAction
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $eventId = $request->query('event_id');
        
        if (!$eventId) {
            return $this->responder->error('MISSING_EVENT_ID', 400, 'Event ID is required');
        }

        $groups = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($g) => EventStaffingGroupPresenter::fromDomain($g), $groups)
        );
    }
}
