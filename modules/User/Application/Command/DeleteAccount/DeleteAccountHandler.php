<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteAccount;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Exception\UserNotFoundException;

final readonly class DeleteAccountHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(DeleteAccountCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if ($user === null) {
            throw UserNotFoundException::withId($command->userId);
        }

        $user->softDelete();

        $this->userRepository->save($user);
    }
}
