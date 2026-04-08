<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Domain\Exception\CredentialsInvalidException;
use Modules\IAM\Domain\Exception\UserNotVerifiedException;
use Modules\IAM\Domain\Exception\UserNotApprovedException;
use Modules\IAM\Domain\Exception\UserPendingException;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Enum\RoleSlugEnum;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
        private TokenManager $tokenManager,
        private UserJoinRequestRepositoryInterface $userJoinRequestRepository,
        private RoleRepository $roleRepository,
    ) {}

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = $this->userRepository->findByEmail($command->email);
        if (!$user || !$this->passwordHasher->check($command->password, $user->password)) {
            throw new CredentialsInvalidException();
        }

        $employeeRole = $this->roleRepository->findBySlug(RoleSlugEnum::EMPLOYEE);

        if ($employeeRole && $user->hasRole($employeeRole->uuid)) {
            $latestJoinRequest = $this->userJoinRequestRepository->findLatestByUserId($user->uuid);
            if ($latestJoinRequest) {
                if ($latestJoinRequest->status->isRejected()) {
                    throw UserNotApprovedException::forUser();
                }
                if ($latestJoinRequest->status->isPending()) {
                    throw UserPendingException::create();
                }
            }

            if (!$user->emailVerifiedAt) {
                throw UserNotVerifiedException::forEmail();
            }
        }

        return [
            'tokens' => $this->tokenManager->createToken($user->uuid->value),
            'user' => $user,
        ];
    }

}
