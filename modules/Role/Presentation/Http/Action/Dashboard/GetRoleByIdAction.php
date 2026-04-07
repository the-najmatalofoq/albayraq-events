<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Action\Dashboard;

use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Role\Presentation\Http\Presenter\RolePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final class GetRoleByIdAction
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id)
    {
        $role = $this->repository->findById(RoleId::fromString($id));

        if (!$role) {
            return $this->responder->notFound('messages.role_not_found');
        }

        return $this->responder->success(RolePresenter::fromDomain($role));
    }
}
