<?php
// modules/EventStaffingPositionRequirement/Presentation/Http/Action/ListEventStaffingPositionRequirementsAction.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventStaffingPositionRequirement\Domain\Repository\EventStaffingPositionRequirementRepositoryInterface;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventStaffingPositionRequirement\Presentation\Http\Presenter\EventStaffingPositionRequirementPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventStaffingPositionRequirementsAction
{
    public function __construct(
        private EventStaffingPositionRequirementRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(Request $request): mixed
    {
        $positionId = $request->query('position_id');

        if (!$positionId) {
            return $this->responder->error('MISSING_POSITION_ID', 400, 'Position ID is required');
        }

        $requirements = $this->repository->findByPositionId(PositionId::fromString($positionId));

        return $this->responder->success(
            data: array_map(fn($r) => EventStaffingPositionRequirementPresenter::fromDomain($r), $requirements)
        );
    }
}
