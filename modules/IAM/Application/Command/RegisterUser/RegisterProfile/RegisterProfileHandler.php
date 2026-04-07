<?php
// modules/IAM/Application/Command/RegisterUser/RegisterProfile/RegisterProfileHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

use DateTimeImmutable;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Enum\GenderEnum;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;

final readonly class RegisterProfileHandler
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
    ) {
    }

    public function handle(RegisterProfileCommand $command): void
    {
        $profile = EmployeeProfile::create(
            uuid: $this->profileRepository->nextIdentity(),
            userId: $command->userId,
            fullName: $command->fullName,
            identityNumber: $command->identityNumber,
            nationalityId: $command->nationalityId,
            birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : null,
            gender: $command->gender ? GenderEnum::from($command->gender) : null,
            height: $command->height,
            weight: $command->weight
        );

        $this->profileRepository->save($profile);
    }
}
