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

use Modules\Geography\Application\Service\GeoValidationService;
use Modules\Geography\Domain\ValueObject\CityId;
use Modules\User\Domain\ValueObject\EmployeeNationality;

final readonly class UpdateUserProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmployeeProfileRepositoryInterface $profileRepository,
        private GeoValidationService $geoValidationService,
    ) {
    }

    public function handle(UpdateUserProfileCommand $command): void
    {
        $userId = new UserId($command->userId);
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return;
        }

        if ($command->nationalId !== null) {
            $user->updateNationalId($command->nationalId);
            $this->userRepository->save($user);
        }

        $cityId = $command->cityId ? new CityId($command->cityId) : null;
        $nationalities = array_map(
            fn(array $n) => EmployeeNationality::create($n['id'], (bool) ($n['is_primary'] ?? false)),
            $command->nationalities
        );

        if (!empty($command->nationalities) || $cityId) {
            $this->geoValidationService->validateProfileGeo(
                $cityId,
                $command->nationalities
            );
        }

        $profile = $this->profileRepository->findByUserId($userId);
        
        if ($profile) {
            $profile->update(
                birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : $profile->birthDate,
                cityId: $cityId ?? $profile->cityId,
                nationalities: !empty($nationalities) ? $nationalities : $profile->nationalities,
                gender: $command->gender ? GenderEnum::from($command->gender) : $profile->gender,
                height: $command->height ?? $profile->height,
                weight: $command->weight ?? $profile->weight
            );
        } else {
            $profile = EmployeeProfile::create(
                uuid: $this->profileRepository->nextIdentity(),
                userId: $userId,
                birthDate: $command->birthDate ? new DateTimeImmutable($command->birthDate) : null,
                cityId: $cityId,
                nationalities: $nationalities,
                gender: $command->gender ? GenderEnum::from($command->gender) : null,
                height: $command->height,
                weight: $command->weight,
                createdAt: new DateTimeImmutable()
            );
        }

        $this->profileRepository->save($profile);
    }
}
