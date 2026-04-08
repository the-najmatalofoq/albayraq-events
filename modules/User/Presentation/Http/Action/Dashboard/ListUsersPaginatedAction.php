<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\UserPresenter;

final readonly class ListUsersPaginatedAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(BaseFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->userRepository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($user) => UserPresenter::fromDomain($user)
        );
    }
}
