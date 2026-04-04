<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Illuminate\Http\UploadedFile;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $userId,
        public array|string $name,
        public ?string $email,
        public string $phone,
        public string $password,
        public ?string $nationalId = null,
        public ?string $birthDate = null,
        public ?string $cityId = null,
        public array $nationalities = [],
        public ?string $gender = null,
        public ?float $height = null,
        public ?float $weight = null,
        public string $accountOwner = '',
        public string $bankName = '',
        public string $iban = '',
        public array $contactPhones = [],
        public ?UploadedFile $avatar = null,
        public ?UploadedFile $idCopy = null,
    ) {
    }
}
