<?php
// modules/User/Application/Command/UpdateUserProfile/UpdateUserProfileHandler.php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateUserProfile;

use DateTimeImmutable;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\Enum\GenderEnum;
use Modules\User\Domain\EmployeeProfile;
use Modules\Shared\Domain\ValueObject\TranslatableText;


final readonly class UpdateUserProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmployeeProfileRepositoryInterface $profileRepository,
    ) {}

    public function handle(UpdateUserProfileCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if (!$user) {
            return;
        }

        $profile = $this->profileRepository->findByUserId($command->userId);
        $fullName = array_merge($profile->fullName->values, $command->fullName->values);
        if ($profile) {
            $profile->update(
                fullName: TranslatableText::fromArray($fullName),
                identityNumber: $command->identityNumber,
                nationalityId: $command->nationalityId,
                birthDate: $command->birthDate ? $command->birthDate : $profile->birthDate,
                gender: $command->gender ? GenderEnum::from($command->gender)->value : $profile->gender,
                height: $command->height ?? $profile->height,
                weight: $command->weight ?? $profile->weight
            );
        } else {
            $profile = EmployeeProfile::create(
                uuid: $this->profileRepository->nextIdentity(),
                userId: $command->userId,
                fullName: $command->fullName,
                identityNumber: $command->identityNumber,
                nationalityId: $command->nationalityId,
                birthDate: $command->birthDate ? $command->birthDate : null,
                gender: $command->gender ? GenderEnum::from($command->gender) : null,
                height: $command->height,
                weight: $command->weight
            );
        }

        $this->profileRepository->save($profile);
    }
}
