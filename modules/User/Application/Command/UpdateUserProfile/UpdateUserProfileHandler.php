<?php
// modules/User/Application/Command/UpdateUserProfile/UpdateUserProfileHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserProfile;

use DateTimeImmutable;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\Enum\GenderEnum;
use Modules\User\Domain\EmployeeProfile;

final readonly class UpdateUserProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmployeeProfileRepositoryInterface $profileRepository,
    ) {
    }

    public function handle(UpdateUserProfileCommand $command): void
    {
        $userId = new UserId($command->userId);
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return;
        }

        // 1. Update User (National ID if provided)
        if ($command->nationalId !== null) {
            $user->updateNationalId($command->nationalId);
            $this->userRepository->save($user);
        }

        // 2. Update/Create Profile
        $profile = $this->profileRepository->findByUserId($userId);
        
        if ($profile) {
            $profile->update(
                birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : $profile->birthDate,
                nationality: $command->nationality ?? $profile->nationality,
                gender: $command->gender ? GenderEnum::from($command->gender) : $profile->gender,
                height: $command->height ?? $profile->height,
                weight: $command->weight ?? $profile->weight
            );
        } else {
            $profile = EmployeeProfile::create(
                uuid: $this->profileRepository->nextIdentity(),
                userId: $userId,
                birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : null,
                nationality: $command->nationality,
                gender: $command->gender ? GenderEnum::from($command->gender) : null,
                height: $command->height,
                weight: $command->weight,
                createdAt: new DateTimeImmutable()
            );
        }

        $this->profileRepository->save($profile);
    }
}
