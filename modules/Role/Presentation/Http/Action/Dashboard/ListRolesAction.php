<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Action\Dashboard;

use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Presentation\Http\Presenter\RolePresenter;
use Modules\Role\Presentation\Http\Request\Dashboard\RoleFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final class ListRolesAction
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(RoleFilterRequest $request)
    {
        $criteria = $request->toFilterCriteria();
        $roles = $this->repository->all($criteria);

        return $this->responder->success(
            $roles->map(fn($role) => RolePresenter::fromDomain($role))->toArray()
        );
    }
}
