<?php
// modules/User/Presentation/Http/Presenter/EmployeeProfilePresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Carbon\Carbon;
use Modules\User\Domain\EmployeeProfile;

final class EmployeeProfilePresenter
{
    public static function fromDomain(EmployeeProfile $profile): array
    {
        return [
            'id' => $profile->uuid->value,
            'user_id' => $profile->userId->value,
            'birth_date' => $profile->birthDate?->format('Y-m-d'),
            'nationality' => $profile->nationality,
            'gender' => $profile->gender?->value,
            'height' => $profile->height,
            'weight' => $profile->weight,
            'created_at' => Carbon::instance($profile->createdAt)->toIso8601String(),
        ];
    }
}
