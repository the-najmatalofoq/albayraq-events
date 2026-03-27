<?php
// modules/User/Presentation/Http/Presenter/UserProfilePresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\UserProfile;

final class UserProfilePresenter
{
    public static function fromDomain(UserProfile $profile): array
    {
        return [
            'id' => $profile->uuid->value,
            'employee_number' => $profile->employeeNumber,
            'job_title' => $profile->jobTitle->toArray(),
            'department' => $profile->department->toArray(),
            'hiring_date' => $profile->hiringDate?->format('Y-m-d'),
            'is_active' => $profile->isActive,
        ];
    }
}
