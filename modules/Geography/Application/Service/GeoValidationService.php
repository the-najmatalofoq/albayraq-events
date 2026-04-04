<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Service;

use Illuminate\Validation\ValidationException;
use Modules\Geography\Domain\Repository\{
    CityRepositoryInterface,
    NationalityRepositoryInterface
};
use Modules\Geography\Domain\ValueObject\CityId;
use Modules\Geography\Domain\ValueObject\NationalityId;

final class GeoValidationService
{
    public function __construct(
        private readonly CityRepositoryInterface $cityRepository,
        private readonly NationalityRepositoryInterface $nationalityRepository
    ) {
    }

    public function validateProfileGeo(?CityId $cityId, array $nationalityIds): void
    {
        if (empty($nationalityIds)) {
            throw ValidationException::withMessages([
                'profile.nationalities' => __('validation.required', ['attribute' => 'nationalities'])
            ]);
        }

        $primaryCount = count(array_filter($nationalityIds, fn($n) => $n['is_primary']));
        if ($primaryCount !== 1) {
            throw ValidationException::withMessages([
                'profile.nationalities' => 'Exactly one nationality must be marked as primary.'
            ]);
        }

        if ($cityId) {
            $city = $this->cityRepository->findById($cityId);
            if (!$city) {
                throw ValidationException::withMessages([
                    'profile.city_id' => __('validation.exists', ['attribute' => 'city_id'])
                ]);
            }

            $natCountryIds = [];
            foreach ($nationalityIds as $nat) {
                $nationality = $this->nationalityRepository->findById(
                    new NationalityId($nat['id'])
                );
                if ($nationality) {
                    $natCountryIds[] = $nationality->countryId()->value;
                }
            }

            if (!in_array($city->countryId()->value, $natCountryIds, true)) {
                throw ValidationException::withMessages([
                    'profile.city_id' => 'The selected city does not belong to any of the user\'s registered nationalities.'
                ]);
            }
        }
    }
}
