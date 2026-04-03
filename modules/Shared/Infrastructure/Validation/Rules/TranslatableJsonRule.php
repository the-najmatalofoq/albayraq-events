<?php
// modules/Shared/Infrastructure/Validation/Rules/TranslatableJsonRule.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

// fix: use private helper methods
final class TranslatableJsonRule implements ValidationRule
{
    public function __construct(
        private readonly bool $arRequired = true,
        private readonly bool $enRequired = true,
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $fail('validation.translatable_json')->translate();
                return;
            }
            $value = $decoded;
        }

        if (!is_array($value)) {
            $fail('validation.translatable_json')->translate();
            return;
        }

        if ($this->arRequired && (!isset($value['ar']) || trim((string) $value['ar']) === '')) {
            $fail('validation.translatable_ar_required')->translate();
        }

        if ($this->enRequired && (!isset($value['en']) || trim((string) $value['en']) === '')) {
            $fail('validation.translatable_en_required')->translate();
        }
    }
}
