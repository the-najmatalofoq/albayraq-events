<?php
// modules/IAM/Application/Command/RegisterUser/RegisterProfile/RegisterProfileHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

use DateTimeImmutable;
use Modules\User\Domain\EmployeeProfile;
use Modules\User\Domain\Enum\GenderEnum;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;

use Modules\Geography\Application\Service\GeoValidationService;
use Modules\Geography\Domain\ValueObject\CityId;
use Modules\User\Domain\ValueObject\EmployeeNationality;

final readonly class RegisterProfileHandler
{
    public function __construct(
        private EmployeeProfileRepositoryInterface $profileRepository,
        private GeoValidationService $geoValidationService,
    ) {
    }

    public function handle(RegisterProfileCommand $command): void
    {
        $cityId = $command->cityId ? new CityId($command->cityId) : null;
        $nationalities = array_map(
            fn(array $n) => EmployeeNationality::create($n['id'], (bool) ($n['is_primary'] ?? false)),
            $command->nationalities
        );

        $this->geoValidationService->validateProfileGeo(
            $cityId,
            $command->nationalities
        );

        $profile = EmployeeProfile::create(
            uuid: $this->profileRepository->nextIdentity(),
            userId: new UserId($command->userId),
            birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : null,
            cityId: $cityId,
            nationalities: $nationalities,
            gender: $command->gender ? GenderEnum::from($command->gender) : null,
            height: $command->height,
            weight: $command->weight,
            createdAt: new DateTimeImmutable()
        );

        $this->profileRepository->save($profile);
    }
}
