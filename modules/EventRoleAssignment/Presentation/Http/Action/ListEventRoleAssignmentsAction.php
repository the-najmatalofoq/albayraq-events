<?php
// modules/EventRoleAssignment/Presentation/Http/Action/ListEventRoleAssignmentsAction.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventRoleAssignment\Presentation\Http\Presenter\EventRoleAssignmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventRoleAssignmentsAction
{
    public function __construct(
        private EventRoleAssignmentRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $eventId = $request->query('event_id');
        
        if (!$eventId) {
            return $this->responder->error('MISSING_EVENT_ID', 400, 'Event ID is required');
        }

        $assignments = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($a) => EventRoleAssignmentPresenter::fromDomain($a), $assignments)
        );
    }
}
