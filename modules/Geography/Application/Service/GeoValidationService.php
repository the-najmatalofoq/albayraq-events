<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Service;

use Illuminate\Validation\ValidationException;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;

final class GeoValidationService
{
    /**
     * Rules:
     * 1. If city_id is set, city's country must be in the user's nationality countries
     * 2. Exactly one nationality must be marked is_primary = true
     * 3. At least one nationality required
     */
    public function validateProfileGeo(EmployeeProfileModel $profile): void
    {
        $nationalities = $profile->nationalities;
        
        if ($nationalities->isEmpty()) {
            throw ValidationException::withMessages([
                'profile.nationalities' => __('validation.required', ['attribute' => 'nationalities'])
            ]);
        }
        
        $primaryCount = $nationalities->where('pivot.is_primary', true)->count();
        if ($primaryCount !== 1) {
            throw ValidationException::withMessages([
                'profile.nationalities' => 'Exactly one nationality must be marked as primary.'
            ]);
        }
        
        if ($profile->city_id) {
            $city = $profile->city;
            if ($city && !$nationalities->pluck('country_id')->contains($city->country_id)) {
                throw ValidationException::withMessages([
                    'profile.city_id' => 'The selected city does not belong to any of the user\'s registered nationalities.'
                ]);
            }
        }
    }
}
