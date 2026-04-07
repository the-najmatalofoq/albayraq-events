<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Services;

use Illuminate\Support\Facades\Hash;
use Modules\User\Domain\ValueObject\HashedPassword;
use Modules\IAM\Domain\Service\PasswordHasher;

final readonly class BcryptPasswordHasher implements PasswordHasher
{
    public function hash(string $plainPassword): HashedPassword
    {
        return new HashedPassword(Hash::make($plainPassword));
    }

    public function verify(string $plainPassword, HashedPassword $hashedPassword): bool
    {
        return Hash::check($plainPassword, $hashedPassword->value);
    }
}
