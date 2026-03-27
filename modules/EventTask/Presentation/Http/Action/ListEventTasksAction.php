<?php
// modules/EventTask/Presentation/Http/Action/ListEventTasksAction.php
declare(strict_types=1);

namespace Modules\EventTask\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventTask\Domain\Repository\EventTaskRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventTask\Presentation\Http\Presenter\EventTaskPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventTasksAction
{
    public function __construct(
        private EventTaskRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(Request $request): mixed
    {
        $eventId = $request->query('event_id');

        if (!$eventId) {
            return $this->responder->error('MISSING_EVENT_ID', 400, 'Event ID is required');
        }

        $tasks = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($t) => EventTaskPresenter::fromDomain($t), $tasks)
        );
    }
}
