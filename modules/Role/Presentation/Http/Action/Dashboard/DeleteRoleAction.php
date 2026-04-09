<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Action\Dashboard;

use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Presentation\Http\JsonResponder;

final class DeleteRoleAction
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly JsonResponder $responder,
    ) {}

    public function __invoke(string $id)
    {
        $roleId = RoleId::fromString($id);
        $role = $this->repository->findById($roleId);

        if (!$role) {
            return $this->responder->notFound(__('messages.not_found'));
        }

        $this->repository->delete($roleId);

        return $this->responder->noContent();
    }
}
