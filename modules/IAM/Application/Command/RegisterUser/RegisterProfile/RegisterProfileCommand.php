<?php
// modules/IAM/Application/Command/RegisterUser/DTOs/RegisterProfileData.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterProfile;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class RegisterProfileCommand
{
    public function __construct(
        public TranslatableText $fullName,
        public string $birthDate,
        public string $nationality,
        public string $gender,
        public float $height,
        public float $weight,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            fullName: TranslatableText::fromMixed($data['full_name'] ?? $data['name']),
            birthDate: $data['birth_date'],
            nationality: $data['nationality'],
            gender: $data['gender'],
            height: (float) ($data['height'] ?? 0),
            weight: (float) ($data['weight'] ?? 0)
        );
    }
}
