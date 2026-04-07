<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserSettings/RegisterUserSettingsHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterUserSettings;

use DateTimeImmutable;
use Modules\User\Domain\Enum\LanguageEnum;
use Modules\User\Domain\Repository\UserSettingsRepositoryInterface;
use Modules\User\Domain\UserSettings;

final readonly class RegisterUserSettingsHandler
{
    public function __construct(
        private UserSettingsRepositoryInterface $userSettingsRepository,
    ) {}

    public function handle(RegisterUserSettingsCommand $command): void
    {
        $locale = $command->preferredLocale ? LanguageEnum::from($command->preferredLocale) : LanguageEnum::AR;

        $settings = UserSettings::create(
            uuid: $this->userSettingsRepository->nextIdentity(),
            userId: $command->userId,
            preferredLocale: $locale,
            createdAt: new DateTimeImmutable()
        );

        $this->userSettingsRepository->save($settings);
    }
}
