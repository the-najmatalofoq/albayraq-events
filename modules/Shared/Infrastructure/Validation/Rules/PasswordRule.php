<?php
// modules/Shared/Infrastructure/Validation/Rules/PasswordRule.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatableString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('The ' . $attribute . ' must be a string.');
            return;
        }

        if (strlen($value) < 8) {
            $fail('The ' . $attribute . ' must be at least 8 characters long.');
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('The ' . $attribute . ' must contain at least one lowercase letter.');
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('The ' . $attribute . ' must contain at least one uppercase letter.');
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('The ' . $attribute . ' must contain at least one number.');
        }

        // Optional: special characters
        // if (!preg_match('/[\W]/', $value)) {
        //     $fail('The ' . $attribute . ' must contain at least one special character.');
        // }
    }
}
