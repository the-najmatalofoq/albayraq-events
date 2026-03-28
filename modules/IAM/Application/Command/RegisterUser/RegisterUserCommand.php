<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class RegisterUserCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $phone,
        public string $password,
        public ?string $email = null,
    ) {
    }
}
