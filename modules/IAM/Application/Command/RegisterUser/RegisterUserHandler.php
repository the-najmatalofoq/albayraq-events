<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\IAM\Domain\Exception\UserAlreadyExistsException;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Application\EventDispatcher;
use Modules\Shared\Application\EventDispatchingHandler;
use Modules\Shared\Domain\ValueObject\FilePath;
use Modules\User\Domain\ValueObject\Phone;

final readonly class RegisterUserHandler extends EventDispatchingHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private RoleRepository $roleRepository,
        private PasswordHasher $hasher,
        //  Phone $phone,
        EventDispatcher $dispatcher,
    ) {
        parent::__construct($dispatcher);
    }

    public function handle(RegisterUserCommand $command): UserId
    {
        return DB::transaction(function () use ($command) {
            if ($this->repository->findByPhone($command->phone)) {
                throw UserAlreadyExistsException::withPhone($command->phone);
            }

            if ($command->email && $this->repository->findByEmail($command->email)) {
                throw UserAlreadyExistsException::withEmail($command->email);
            }

            $defaultRole = $this->roleRepository->findBySlug(RoleSlugEnum::INDIVIDUAL);
            if (!$defaultRole) {
                throw new \RuntimeException('Default role (individual) not found');
            }

            $userId = $this->repository->nextIdentity();
            $user = User::register(
                uuid: $userId,
                name: $command->name,
                email: $command->email,
                phone: new Phone($command->phone),
                avatar: new FilePath($command->avatar),
                password: $this->hasher->hash($command->password),
                roleIds: [$defaultRole->uuid],
                createdAt: new DateTimeImmutable(),
            );

            $this->repository->save($user);
            $this->dispatchEvents($user);

            return $userId;
        });
    }
}
