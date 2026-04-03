<?php
// modules/User/Application/Command/UpdateUserAvatar/UpdateUserAvatarHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserAvatar;

use Modules\Shared\Domain\Service\FileStorageInterface;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateUserAvatarHandler
{
    public function __construct(
        private FileStorageInterface $fileStorage,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(UpdateUserAvatarCommand $command): void
    {
        $userId = new UserId($command->userId);
        
        $filePath = $this->fileStorage->uploadForUser(
            $command->avatar,
            $userId,
            'avatar'
        );

        $user = $this->userRepository->findById($userId);
        if ($user) {
            $user->updateAvatar($filePath);
            $this->userRepository->save($user);
        }
    }
}
