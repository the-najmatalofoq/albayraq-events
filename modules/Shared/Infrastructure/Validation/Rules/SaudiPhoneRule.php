<?php
// modules/Shared/Infrastructure/Validation/Rules/SaudiPhoneRule.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class SaudiPhoneRule implements ValidationRule
{
    private const PATTERN = '/^(?:\+966|966|0)?5\d{8}$/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !preg_match(self::PATTERN, $value)) {
            $fail('validation.saudi_phone')->translate();
        }
    }
}
