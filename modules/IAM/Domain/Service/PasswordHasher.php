<?php
// modules/IAM/Domain/Service/PasswordHasher.php
declare(strict_types=1);

namespace Modules\IAM\Domain\Service;

use Modules\User\Domain\ValueObject\HashedPassword;

interface PasswordHasher
{
    public function hash(string $plainPassword): HashedPassword;

    public function check(string $plainPassword, HashedPassword $hashedPassword): bool;
}
