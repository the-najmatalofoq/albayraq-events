<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Domain\Exception\UserNotActiveException;
use Modules\IAM\Domain\Exception\CredentialsInvalidException;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
        private TokenManagerInterface $tokenManager,
    ) {
    }

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = $this->userRepository->findByPhone($command->phone);

        if (!$user || !$this->passwordHasher->check($command->password, $user->password)) {
            throw new CredentialsInvalidException("Invalid credentials.");
        }

        if (!$user->isActive) {
            throw UserNotActiveException::forUser($user->uuid->value);
        }

        return $this->tokenManager->issueFromUserId($user->uuid);
    }
}
