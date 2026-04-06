<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Domain\Exception\CredentialsInvalidException;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
        private TokenManager $tokenManager,
    ) {}

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = $this->userRepository->findByEmail($command->email);

        if (!$user || !$this->passwordHasher->check($command->password, $user->password)) {
            throw new CredentialsInvalidException("Invalid credentials.");
        }


        return [
            'tokens' => $this->tokenManager->createToken($user->uuid->value),
            'user' => $user,
        ];
    }
}
