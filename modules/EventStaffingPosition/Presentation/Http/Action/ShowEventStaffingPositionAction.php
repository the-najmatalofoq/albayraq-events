<?php
// modules/EventStaffingPosition/Presentation/Http/Action/ShowEventStaffingPositionAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowEventStaffingPositionAction
{
    public function __construct(
        private EventStaffingPositionRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $position = $this->repository->findById(PositionId::fromString($id));

        if ($position === null) {
            return $this->responder->error(errorCode: 'NOT_FOUND', status: 404);
        }

        return $this->responder->success(data: [
            'id' => $position->uuid->value,
            'title' => $position->title->toArray(),
            'requirements' => $position->requirements->toArray(),
            'headcount' => $position->headcount,
            'wage_amount' => $position->wage?->amount,
            'wage_type' => $position->wage?->currency,
            'is_announced' => $position->isActive,
        ]);
    }
}
