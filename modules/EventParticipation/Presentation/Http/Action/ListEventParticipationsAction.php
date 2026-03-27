<?php
// modules/EventParticipation/Presentation/Http/Action/ListEventParticipationsAction.php
declare(strict_types=1);

namespace Modules\EventParticipation\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventParticipation\Presentation\Http\Presenter\EventParticipationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventParticipationsAction
{
    public function __construct(
        private EventParticipationRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $eventId = $request->query('event_id');
        
        if (!$eventId) {
            return $this->responder->error('MISSING_EVENT_ID', 400, 'Event ID is required');
        }

        $participations = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($p) => EventParticipationPresenter::fromDomain($p), $participations)
        );
    }
}
