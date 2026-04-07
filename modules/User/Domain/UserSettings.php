<?php
// modules/User/Domain/UserSettings.php
declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\Enum\LanguageEnum;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserSettingsId;

final class UserSettings extends AggregateRoot
{
    private function __construct(
        public readonly UserSettingsId $uuid,
        public readonly UserId $userId,
        public private(set) LanguageEnum $preferredLocale,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        UserSettingsId $uuid,
        UserId $userId,
        LanguageEnum $preferredLocale,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            preferredLocale: $preferredLocale,
            createdAt: $createdAt,
        );
    }

    public static function reconstitute(
        UserSettingsId $uuid,
        UserId $userId,
        LanguageEnum $preferredLocale,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            preferredLocale: $preferredLocale,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function changeLocale(LanguageEnum $locale): void
    {
        $this->preferredLocale = $locale;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
