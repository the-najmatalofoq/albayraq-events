<?php
// modules/IAM/Application/Command/RegisterUser/DTOs/RegisterContactPhone.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone;

final readonly class RegisterContactPhoneCommand
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $relation = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            relation: $data['relation'] ?? null,
        );
    }
}
