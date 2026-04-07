<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserSettings/RegisterUserSettingsCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterUserSettings;

use Modules\User\Domain\ValueObject\UserId;

final readonly class RegisterUserSettingsCommand
{
    public function __construct(
        public UserId $userId,
        public ?string $preferredLocale = null,
    ) {}
}
