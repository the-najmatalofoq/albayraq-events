<?php
// modules/EventStaffingPosition/Presentation/Http/Action/ListEventStaffingPositionsAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\EventStaffingPosition\Domain\Repository\EventStaffingPositionRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventStaffingPositionsAction
{
    public function __construct(
        private EventStaffingPositionRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId): JsonResponse
    {
        $positions = $this->repository->findByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(function ($p) {
                return [
                    'id' => $p->uuid->value,
                    'title' => $p->title->toArray(),
                    'requirements' => $p->requirements->toArray(),
                    'headcount' => $p->headcount,
                    'wage_amount' => $p->wage?->amount,
                    'wage_type' => $p->wage?->currency,
                    'is_announced' => $p->isActive,
                ];
            }, $positions)
        );
    }
}
