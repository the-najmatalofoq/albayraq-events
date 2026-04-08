<?php
// modules/User/Domain/Repository/UserSettingsRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\UserSettings;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserSettingsId;

interface UserSettingsRepositoryInterface
{
    public function nextIdentity(): UserSettingsId;

    public function findByUserId(UserId $userId): ?UserSettings;

    public function save(UserSettings $settings): void;
}
