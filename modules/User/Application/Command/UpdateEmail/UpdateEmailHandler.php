<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateEmail;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Exception\UserNotFoundException;

final readonly class UpdateEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(UpdateEmailCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw UserNotFoundException::withId($userId);
        }

        $user->updateEmail($command->email);

        $this->userRepository->save($user);
    }
}
