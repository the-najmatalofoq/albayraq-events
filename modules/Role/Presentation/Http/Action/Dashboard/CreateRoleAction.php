<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Action\Dashboard;

use Modules\Role\Domain\Enum\RoleLevelEnum;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Role;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Role\Presentation\Http\Presenter\RolePresenter;
use Modules\Role\Presentation\Http\Request\Dashboard\StoreRoleRequest;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;

final class CreateRoleAction
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(StoreRoleRequest $request)
    {
        $roleId = $this->repository->nextIdentity();
        
        $role = Role::create(
            uuid: $roleId,
            slug: RoleSlugEnum::from($request->validated('slug')),
            name: TranslatableText::fromArray($request->validated('name')),
            isGlobal: (bool)$request->validated('is_global'),
            level: RoleLevelEnum::from($request->validated('level')),
        );

        $this->repository->save($role);

        return $this->responder->created(
            RolePresenter::fromDomain($role),
            'messages.role_created'
        );
    }
}
