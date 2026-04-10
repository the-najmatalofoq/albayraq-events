<?php
// modules/EventJoinRequest/Presentation/Http/Action/ListJoinRequestsAction.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Presentation\Http\Presenter\EventJoinRequestPresenter;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListJoinRequestsAction
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $status = $request->query('status');
        $requests = $this->repository->findByEventId(
            EventId::fromString($eventId),
            is_string($status) ? $status : null,
        );

        return $this->responder->success(
            data: array_map(fn ($r) => EventJoinRequestPresenter::fromDomain($r), $requests),
        );
    }
}
