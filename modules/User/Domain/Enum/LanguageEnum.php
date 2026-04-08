<?php
// modules/User/Domain/Enum/LanguageEnum.php
declare(strict_types=1);

namespace Modules\User\Domain\Enum;

enum LanguageEnum: string
{
    case AR = 'ar';
    case EN = 'en';

    public function label(): string
    {
        return match ($this) {
            self::AR => 'العربية',
            self::EN => 'English',
        };
    }

    public function isRtl(): bool
    {
        return match ($this) {
            self::AR => true,
            self::EN => false,
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
