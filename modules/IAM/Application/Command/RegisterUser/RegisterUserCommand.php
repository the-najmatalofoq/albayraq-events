<?php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

final readonly class RegisterUserCommand
{
    public function __construct(
        public array $name,
        public ?string $email,
        public string $phone,
        public string $password,
        
    ) {}
}
