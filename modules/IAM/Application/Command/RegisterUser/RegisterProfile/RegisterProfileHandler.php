<?php
// modules/IAM/Application/Command/RegisterUser/RegisterProfile/RegisterProfileHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

use DateTimeImmutable;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Enum\Gender;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\EmployeeProfileId;
use Modules\User\Domain\ValueObject\UserId;
// fix the Gener Enum, and the EmployeeProfileId::next
final readonly class RegisterProfileHandler
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
    ) {}

    public function handle(RegisterProfileCommand $command): void
    {
        $profile = EmployeeProfile::create(
            uuid: EmployeeProfileId::next(),
            userId: new UserId($command->userId),
            birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : null,
            nationality: $command->nationality,
            gender: $command->gender ? Gender::from($command->gender) : null,
            height: $command->height,
            weight: $command->weight,
            createdAt: new DateTimeImmutable()
        );

        $this->profileRepository->save($profile);
    }
}
