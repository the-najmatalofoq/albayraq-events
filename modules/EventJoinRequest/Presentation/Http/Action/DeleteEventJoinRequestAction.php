<?php
// modules/EventJoinRequest/Presentation/Http/Action/DeleteEventJoinRequestAction.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteEventJoinRequestAction
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->repository->delete(JoinRequestId::fromString($id));
        return $this->responder->noContent();
    }
}
