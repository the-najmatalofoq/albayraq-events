<?php
// modules/User/Presentation/Http/Presenter/UserProfilePresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\EmployeeProfile;

final class UserProfilePresenter
{
    public static function fromDomain(EmployeeProfile $profile): array
    {
        return [
            'id' => $profile->uuid->value,
            'user_id' => $profile->userId->value,
            'full_name' => $profile->fullName?->toArray(),
            'birth_date' => $profile->birthDate?->format('Y-m-d'),
            'nationality' => $profile->nationality,
            'gender' => $profile->gender,
            'national_id' => $profile->nationalId,
            'medical_record' => $profile->medicalRecord,
            'height' => $profile->height,
            'weight' => $profile->weight,
        ];
    }
}
