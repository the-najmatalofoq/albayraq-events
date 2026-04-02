<?php
// modules/IAM/Application/Command/RegisterUser/RegisterProfile/RegisterProfileHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

use DateTimeImmutable;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\EmployeeProfileId;

final readonly class RegisterProfileHandler
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
    ) {}

    public function handle(RegisterProfileCommand $command, UserId $userId): EmployeeProfileId
    {
        $profileId = $this->profileRepository->nextIdentity();
        $profile = EmployeeProfile::create($profileId, $userId);

        $profile->updatePersonalData(
            fullName: $command->fullName,
            birthDate: new DateTimeImmutable($command->birthDate),
            nationality: $command->nationality,
            gender: $command->gender,
        );

        $profile->updatePhysicalData(
            height: $command->height,
            weight: $command->weight
        );

        $this->profileRepository->save($profile);

        return $profileId;
    }
}
