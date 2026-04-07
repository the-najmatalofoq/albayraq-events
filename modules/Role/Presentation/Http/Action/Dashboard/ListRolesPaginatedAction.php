<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Action\Dashboard;

use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Presentation\Http\Presenter\RolePresenter;
use Modules\Role\Presentation\Http\Request\Dashboard\RoleFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final class ListRolesPaginatedAction
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(RoleFilterRequest $request)
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->repository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($role) => RolePresenter::fromDomain($role)
        );
    }
}
