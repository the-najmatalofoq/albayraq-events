<?php
// modules/EventPositionApplication/Presentation/Http/Action/ListEventPositionApplicationsAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventPositionApplication\Presentation\Http\Presenter\EventPositionApplicationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventPositionApplicationsAction
{
    public function __construct(
        private EventPositionApplicationRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $positionId = $request->query('position_id');
        
        if (!$positionId) {
            return $this->responder->error('MISSING_POSITION_ID', 400, 'Position ID is required');
        }

        $applications = $this->repository->findByPositionId(PositionId::fromString($positionId));

        return $this->responder->success(
            data: array_map(fn($a) => EventPositionApplicationPresenter::fromDomain($a), $applications)
        );
    }
}
