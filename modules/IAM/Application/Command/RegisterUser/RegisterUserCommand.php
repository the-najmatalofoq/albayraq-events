<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Illuminate\Http\UploadedFile;
use Modules\Shared\Application\Command\CommandInterface;

// fix: what is the CommandInterface? 
final readonly class RegisterUserCommand implements CommandInterface
{
    public function __construct(
        public string $userId,
        public array|string $name,
        public ?string $email,
        public string $phone,
        public string $password,
        public string $nationalId,
        public ?string $birthDate = null,
        public ?string $nationality = null,
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
