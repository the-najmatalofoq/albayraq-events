<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
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
        $filters = $request->toFilterCriteria()->toArray();
        $perPage = $request->getPerPage();

        $paginator = $this->userRepository->paginate($perPage, $filters);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: new PaginationCriteria(
                page: $paginator->currentPage(),
                perPage: $paginator->perPage()
            ),
            presenter: fn($user) => UserPresenter::fromDomain($user)
        );
    }
}
