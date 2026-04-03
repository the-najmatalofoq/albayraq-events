<?php
// modules/Shared/Infrastructure/Validation/Rules/TranslatableJsonRule.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class TranslatableJsonRule implements ValidationRule
{
    public function __construct(
        private readonly bool $arRequired = true,
        private readonly bool $enRequired = true,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = $this->decodeValue($value, $fail);
        if ($value === null) {
            return;
        }

        $this->checkRequiredLocale($value, 'ar', $this->arRequired, $fail);
        $this->checkRequiredLocale($value, 'en', $this->enRequired, $fail);
    }

    private function decodeValue(mixed $value, Closure $fail): ?array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $fail('validation.translatable_json')->translate();
                return null;
            }
            $value = $decoded;
        }

        if (!is_array($value)) {
            $fail('validation.translatable_json')->translate();
            return null;
        }

        return $value;
    }

    private function checkRequiredLocale(array $value, string $locale, bool $isRequired, Closure $fail): void
    {
        if ($isRequired && (!isset($value[$locale]) || trim((string) $value[$locale]) === '')) {
            $fail("validation.translatable_{$locale}_required")->translate();
        }
    }
}
