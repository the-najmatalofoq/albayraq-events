<?php
// modules/User/Infrastructure/Persistence/Eloquent/Repositories/EloquentUserSettingsRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\Repository\UserSettingsRepositoryInterface;
use Modules\User\Domain\UserSettings;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserSettingsId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserSettingsModel;
use Modules\User\Infrastructure\Persistence\UserSettingsReflector;

final class EloquentUserSettingsRepository implements UserSettingsRepositoryInterface
{
    public function __construct(
        private readonly UserSettingsReflector $reflector,
        private readonly UserSettingsModel $model,
    ) {}

    public function nextIdentity(): UserSettingsId
    {
        return UserSettingsId::generate();
    }

    public function findByUserId(UserId $userId): ?UserSettings
    {
        $model = $this->model
            ->where('user_id', $userId->value)
            ->first();

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function save(UserSettings $settings): void
    {
        $data = $this->reflector->fromEntity($settings);

        /** @var UserSettingsModel $model */
        $model = $this->model->find($settings->uuid->value) ?? new UserSettingsModel();

        $model->id = $settings->uuid->value;
        $model->fill($data);
        $model->save();
    }
}
