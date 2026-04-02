<?php
// modules/IAM/Application/Command/RegisterUser/DTOs/RegisterAuthData.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class RegisterAuthCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $phone,
        public string $password,
        public ?string $email = null,
        public ?string $avatar = null,
    ) {}

    // todo: only rename from "fromRequest" to "fromData"
    public static function fromRequest(array $data): self
    {
        return new self(
            name: TranslatableText::fromMixed($data['name']),
            phone: $data['phone'],
            password: $data['password'],
            avatar: $data['avatar'],
            email: $data['email'],
        );
    }
}
