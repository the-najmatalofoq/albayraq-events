<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\Authenticate;

use Modules\IAM\Domain\Exception\InvalidCredentialsException;
use Modules\IAM\Domain\Exception\UserNotFoundException;
use Modules\IAM\Domain\Repository\UserRepositoryInterface;
use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\IAM\Domain\Service\TokenManager;

final readonly class AuthenticateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private PasswordHasher $hasher,
        private TokenManager $tokenManager,
    ) {}

    public function handle(AuthenticateUserCommand $command): array
    {
        $user = $this->repository->findByEmail($command->email);
        if (!$user) {
            throw UserNotFoundException::withEmail($command->email);
        }

        if (!$this->hasher->verify($command->password, $user->password)) {
            throw InvalidCredentialsException::create();
        }

        return [
            'token' => $this->tokenManager->createToken($user->email),
            'user_id' => $user->uuid->value,
        ];
    }
}
