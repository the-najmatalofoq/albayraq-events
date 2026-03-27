<?php
// modules/User/Infrastructure/Persistence/UserProfileReflector.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use Modules\User\Domain\UserProfile;
use Modules\User\Domain\ValueObject\UserProfileId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Infrastructure\Persistence\Eloquent\UserProfileModel;

final class UserProfileReflector
{
    public static function fromModel(UserProfileModel $model): UserProfile
    {
        $reflection = new \ReflectionClass(UserProfile::class);
        $userProfile = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => UserProfileId::fromString($model->id),
            'userId' => UserId::fromString($model->user_id),
            'employeeNumber' => $model->employee_number,
            'jobTitle' => TranslatableText::fromArray($model->job_title),
            'department' => TranslatableText::fromArray($model->department),
            'hiringDate' => $model->hiring_date ? \DateTimeImmutable::createFromMutable($model->hiring_date) : null,
            'isActive' => $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($userProfile, $value);
        }

        return $userProfile;
    }
}
