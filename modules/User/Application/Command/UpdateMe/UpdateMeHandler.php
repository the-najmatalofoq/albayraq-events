<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateMe;

use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Exception\UserNotFoundException;

final readonly class UpdateMeHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(UpdateMeCommand $command): void
    {
       
        $user = $this->userRepository->findById($command->userId);

        if ($user === null) {
            throw UserNotFoundException::withId($command->userId);
        }

        $user->updateInfo(
            name: $command->name,
            phone: $command->phone
        );

        $this->userRepository->save($user);
    }
}
