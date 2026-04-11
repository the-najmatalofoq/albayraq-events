<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\UpdatePassword;

use Modules\IAM\Domain\Service\PasswordHasher;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Exception\UserNotFoundException;
use Modules\User\Domain\Exception\InvalidPasswordException;

final readonly class UpdatePasswordHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
    ) {}

    public function handle(UpdatePasswordCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw UserNotFoundException::withId($userId);
        }

        if (!$this->passwordHasher->verify($command->currentPassword, $user->password)) {
            throw new InvalidPasswordException('Current password does not match.');
        }

        $user->changePassword($this->passwordHasher->hash($command->newPassword));

        $this->userRepository->save($user);
    }
}
