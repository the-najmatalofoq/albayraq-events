<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\IAM\Domain\Exception\InvalidCredentialsException;
use Modules\IAM\Domain\Exception\UserNotActiveException;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\Shared\Application\Command\CommandHandlerInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
// fix: the CommandHandlerInterface and the password->verify() method. 
final readonly class AuthenticateUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenManagerInterface $tokenManager,
    ) {
    }

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = filter_var($command->login, FILTER_VALIDATE_EMAIL)
            ? $this->userRepository->findByEmail($command->login)
            : $this->userRepository->findByPhone($command->login);

        if (!$user || !$user->password->verify($command->password)) {
            throw new InvalidCredentialsException();
        }

        if (!$user->isActive) {
            throw new UserNotActiveException();
        }

        return $this->tokenManager->issueFromUserId($user->uuid);
    }
}
