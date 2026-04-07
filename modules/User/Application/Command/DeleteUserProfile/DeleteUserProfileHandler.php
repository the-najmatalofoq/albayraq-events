<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteUserProfile;

use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Exception\ProfileNotFoundException;

final readonly class DeleteUserProfileHandler
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
    ) {
    }

    public function handle(DeleteUserProfileCommand $command): void
    {
        $userId = UserId::fromString($command->userId);
        $profile = $this->profileRepository->findByUserId($userId);

        if ($profile === null) {
            throw ProfileNotFoundException::forUser($userId);
        }

        $profile->softDelete();

        $this->profileRepository->save($profile);
    }
}
