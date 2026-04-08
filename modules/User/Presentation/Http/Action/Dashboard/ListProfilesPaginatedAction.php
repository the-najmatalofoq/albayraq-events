<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\EmployeeProfilePresenter;

final readonly class ListProfilesPaginatedAction
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(BaseFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->profileRepository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($profile) => EmployeeProfilePresenter::fromDomain($profile)
        );
    }
}
