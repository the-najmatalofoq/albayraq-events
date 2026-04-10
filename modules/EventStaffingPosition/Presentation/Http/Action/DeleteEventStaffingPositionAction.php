<?php
// modules/EventStaffingPosition/Presentation/Http/Action/DeleteEventStaffingPositionAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteEventStaffingPositionAction
{
    public function __construct(
        private EventStaffingPositionRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->repository->delete(PositionId::fromString($id));
        return $this->responder->noContent();
    }
}
