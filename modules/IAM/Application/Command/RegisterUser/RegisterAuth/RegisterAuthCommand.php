<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAuth/RegisterAuthCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAuth;

use Modules\User\Domain\ValueObject\Phone;
use Illuminate\Http\UploadedFile;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class RegisterAuthCommand
{
    public function __construct(
        public TranslatableText $name,
        public ?string $email,
        public Phone $phone,
        public string $password,
        public ?UploadedFile $avatar,
    ) {}
}
