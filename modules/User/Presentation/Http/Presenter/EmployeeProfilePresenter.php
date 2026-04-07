<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\EmployeeProfile;

final class EmployeeProfilePresenter
{
    public static function fromDomain(?EmployeeProfile $profile): ?array
    {
        if ($profile === null) {
            return null;
        }

        return [
            'id' => $profile->uuid->value,
            'full_name' => $profile->fullName,
            'identity_number' => $profile->identityNumber,
            'nationality_id' => $profile->nationalityId?->value,
            'birth_date' => $profile->birthDate,
            'gender' => $profile->gender,
            'height' => $profile->height,
            'weight' => $profile->weight,
        ];
    }
}
