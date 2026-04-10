<?php
// modules/EventShiftAssignment/Presentation/Http/Action/ListShiftAssignmentsAction.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventShiftAssignment\Domain\Repository\EventShiftAssignmentRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventShiftAssignment\Presentation\Http\Presenter\ShiftAssignmentPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListShiftAssignmentsAction
{
    public function __construct(
        private EventShiftAssignmentRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $participationId): JsonResponse
    {
        $assignments = $this->repository->findByParticipationId(
            ParticipationId::fromString($participationId)
        );

        return $this->responder->success(
            data: array_map(fn($a) => ShiftAssignmentPresenter::fromDomain($a), $assignments)
        );
    }
}
