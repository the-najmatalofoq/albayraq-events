<?php
// modules/Shared/Infrastructure/Validation/Rules/IbanRule.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\ValidationRule;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class IbanRule implements ValidationRule
{
    private const PATTERN = '/^SA\d{22,24}$/i';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !preg_match(self::PATTERN, $value)) {
            $fail('validation.iban_format')->translate();
        }
    }
}
