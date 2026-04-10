<?php
// modules/EventJoinRequest/Presentation/Http/Action/ListEventJoinRequestsAction.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventJoinRequestsAction
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId): JsonResponse
    {
        $requests = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(function ($r) {
                return [
                    'id' => $r->uuid->value,
                    'user_id' => $r->userId->value,
                    'event_id' => $r->eventId->value,
                    'position_id' => $r->positionId->value,
                    'status' => $r->status->value,
                    'rejection_reason' => $r->rejectionReason,
                    'created_at' => $r->createdAt->format(DATE_ATOM),
                ];
            }, $requests)
        );
    }
}
