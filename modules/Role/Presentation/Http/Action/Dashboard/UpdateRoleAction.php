<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Action\Dashboard;

use Modules\Role\Domain\Enum\RoleLevelEnum;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Role;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Role\Presentation\Http\Presenter\RolePresenter;
use Modules\Role\Presentation\Http\Request\Dashboard\UpdateRoleRequest;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;

final class UpdateRoleAction
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(UpdateRoleRequest $request, string $id)
    {
        $roleId = RoleId::fromString($id);
        $role = $this->repository->findById($roleId);

        if (!$role) {
            return $this->responder->notFound('messages.role_not_found');
        }

        $updatedRole = Role::create(
            uuid: $roleId,
            slug: $request->has('slug') ? RoleSlugEnum::from($request->validated('slug')) : $role->slug,
            name: $request->has('name') ? TranslatableText::fromArray($request->validated('name')) : $role->name,
            isGlobal: $request->has('is_global') ? (bool) $request->validated('is_global') : $role->isGlobal,
            level: $request->has('level') ? RoleLevelEnum::from($request->validated('level')) : $role->level,
        );

        $this->repository->save($updatedRole);

        return $this->responder->success(
            RolePresenter::fromDomain($updatedRole),
            200,
            'messages.role_updated'
        );
    }
}
