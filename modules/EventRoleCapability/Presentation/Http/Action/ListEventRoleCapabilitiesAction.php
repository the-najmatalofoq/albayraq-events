<?php
// modules/EventRoleCapability/Presentation/Http/Action/ListEventRoleCapabilitiesAction.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleCapability\Presentation\Http\Presenter\EventRoleCapabilityPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventRoleCapabilitiesAction
{
    public function __construct(
        private EventRoleCapabilityRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): mixed
    {
        $assignmentId = $request->query('assignment_id');
        
        if (!$assignmentId) {
            return $this->responder->error('MISSING_ASSIGNMENT_ID', 400, 'Assignment ID is required');
        }

        $capabilities = $this->repository->findByAssignmentId(AssignmentId::fromString($assignmentId));

        return $this->responder->success(
            data: array_map(fn($c) => EventRoleCapabilityPresenter::fromDomain($c), $capabilities)
        );
    }
}
