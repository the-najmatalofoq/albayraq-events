<?php
// modules/User/Infrastructure/Persistence/UserSettingsReflector.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\User\Domain\Enum\LanguageEnum;
use Modules\User\Domain\UserSettings;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserSettingsId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserSettingsModel;

final class UserSettingsReflector
{
    public function fromEntity(UserSettings $settings): array
    {
        return [
            'id'               => $settings->uuid->value,
            'user_id'          => $settings->userId->value,
            'preferred_locale' => $settings->preferredLocale->value,
            'created_at'       => $settings->createdAt->format('Y-m-d H:i:s'),
            'updated_at'       => $settings->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function toEntity(UserSettingsModel $model): UserSettings
    {
        return UserSettings::reconstitute(
            uuid: new UserSettingsId($model->id),
            userId: new UserId($model->user_id),
            preferredLocale: LanguageEnum::from($model->preferred_locale instanceof LanguageEnum
                ? $model->preferred_locale->value
                : (string) $model->preferred_locale),
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: $model->updated_at
                ? new DateTimeImmutable($model->updated_at->toDateTimeString())
                : null,
        );
    }
}
