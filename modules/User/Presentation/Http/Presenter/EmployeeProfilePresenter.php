<?php
// modules/User/Presentation/Http/Presenter/EmployeeProfilePresenter.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\EmployeeProfile;

final class EmployeeProfilePresenter
{
    public function present(EmployeeProfile $profile): array
    {
        return [
            'user_id'           => $profile->userId->value(),
            'personal'          => [
                'full_name'     => $profile->fullName,
                'birth_date'    => $profile->birthDate?->format('Y-m-d'),
                'gender'        => $profile->gender,
                'national_id'   => $profile->nationalId,
            ],
            'contact'           => [
                'emergency_contact' => $profile->emergencyContact,
                'emergency_phone'   => $profile->emergencyPhone,
            ],
            'medical'           => [
                'blood_type'        => $profile->bloodType,
                'chronic_diseases'  => $profile->chronicDiseases,
            ],
            'bank'              => [
                'bank_name'         => $profile->bankName,
                'iban'              => $profile->iban,
            ],
            'physical'          => [
                'tshirt_size'       => $profile->tshirtSize,
            ],
            'created_at'        => $profile->createdAt->format(DATE_ATOM),
        ];
    }
}
