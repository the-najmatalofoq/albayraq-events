<?php
// modules/User/Application/Command/UpdateUserAvatar/UpdateUserAvatarHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserAvatar;

use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;

final readonly class UpdateUserAvatarHandler
{
    public function __construct(
        private FileStorageInterface $fileStorage,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(UpdateUserAvatarCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);
        $this->fileStorage->delete($user->avatar);
        $filePath = $this->fileStorage->uploadForUser(
            $command->avatar,
            $command->userId,
            'avatar'
        );

        $user = $this->userRepository->findById($command->userId);
        if ($user) {
            $user->updateAvatar($filePath);
            $this->userRepository->save($user);
        }
    }
}
