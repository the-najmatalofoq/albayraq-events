<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Illuminate\Http\UploadedFile;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\Phone;

final readonly class RegisterUserCommand
{
    public function __construct(
        public TranslatableText $name,
        public ?string $email,
        public Phone $phone,
        public string $password,
        public TranslatableText $fullName,
        public string $identityNumber,
        public NationalityId $nationalityId,
        public ?string $birthDate,
        public ?string $gender, 
        public ?float $height,
        public ?float $weight,
        public string $accountOwner,
        public string $bankName,
        public string $iban,
        public string $contactName,
        public string $contactPhone,
        public string $contactRelation,
        public ?UploadedFile $avatar = null,
        public ?UploadedFile $cv = null,
        public ?UploadedFile $personalIdentity = null,
        public ?UploadedFile $medicalReport = null,
    ) {}
}
