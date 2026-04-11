<?php
// modules/IAM/Application/Command/AuthenticateUser/AuthenticateUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\AuthenticateUser;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\IAM\Domain\Exception\InvalidCredentialsException;
use Modules\IAM\Domain\Service\UserAccessValidator;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
        private TokenManager $tokenManager,
        private UserAccessValidator $accessValidator,
    ) {}

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = $this->userRepository->findByEmail($command->email);
        if (!$user || !$this->passwordHasher->verify($command->password, $user->password)) {
            throw new InvalidCredentialsException(__('messages.errors.credentials_invalid'));
        }

        // $this->accessValidator->validateLogin($user);

        return [
            'tokens' => $this->tokenManager->createToken($user->uuid->value),
            'user' => $user,
        ];
    }
}
