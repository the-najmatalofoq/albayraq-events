<?php
// modules/EventJoinRequest/Presentation/Http/Action/ShowEventJoinRequestAction.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowEventJoinRequestAction
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $request = $this->repository->findById(JoinRequestId::fromString($id));

        if ($request === null) {
            return $this->responder->error(errorCode: 'NOT_FOUND', status: 404);
        }

        return $this->responder->success(data: [
            'id' => $request->uuid->value,
            'user_id' => $request->userId->value,
            'event_id' => $request->eventId->value,
            'position_id' => $request->positionId->value,
            'status' => $request->status->value,
            'rejection_reason' => $request->rejectionReason,
            'created_at' => $request->createdAt->format(DATE_ATOM),
        ]);
    }
}
