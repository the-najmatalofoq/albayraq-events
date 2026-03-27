<?php
// modules/EventOperationalReport/Presentation/Http/Action/ListEventOperationalReportsAction.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventOperationalReport\Domain\Repository\EventOperationalReportRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventOperationalReport\Presentation\Http\Presenter\EventOperationalReportPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventOperationalReportsAction
{
    public function __construct(
        private EventOperationalReportRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $eventId = $request->query('event_id');
        
        if (!$eventId) {
            return $this->responder->error('MISSING_EVENT_ID', 400, 'Event ID is required');
        }

        $reports = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($r) => EventOperationalReportPresenter::fromDomain($r), $reports)
        );
    }
}
