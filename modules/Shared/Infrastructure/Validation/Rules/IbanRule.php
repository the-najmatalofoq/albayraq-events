<?php
// modules/Shared/Infrastructure/Validation/Rules/IbanRule.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class IbanRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('validation.iban.invalid')->translate();
            return;
        }

        $iban = str_replace(' ', '', strtoupper($value));

        if (!preg_match('/^SA[0-9]{22}$/', $iban)) {
            $fail('validation.iban.invalid_saudi')->translate();
        }
    }
}
