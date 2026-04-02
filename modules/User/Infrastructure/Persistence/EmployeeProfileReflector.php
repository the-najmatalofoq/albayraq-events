<?php
// modules/User/Infrastructure/Persistence/UserProfileReflector.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use Modules\User\Domain\ValueObject\UserProfileId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\EmployeeProfile;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Infrastructure\Persistence\Eloquent\EmployeeProfileModel;

final class UserProfileReflector
{
    public static function fromModel(EmployeeProfileModel $model): EmployeeProfile
    {
        $reflection = new \ReflectionClass(EmployeeProfile::class);
        $userProfile = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => UserProfileId::fromString($model->id),
            'userId' => UserId::fromString($model->user_id),
            'fullName' => $model->full_name ? TranslatableText::fromArray($model->full_name) : null,
            'birthDate' => $model->birth_date ? \DateTimeImmutable::createFromFormat('Y-m-d', $model->birth_date) : null,
            'nationality' => $model->nationality,
            'gender' => $model->gender,
            'nationalId' => $model->national_id,
            'height' => $model->height,
            'weight' => $model->weight,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($userProfile, $value);
        }

        return $userProfile;
    }
}
