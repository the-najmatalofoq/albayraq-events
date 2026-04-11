<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\CreateUser;

use Illuminate\Http\UploadedFile;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\Phone;

final readonly class CreateUserCommand
{
    public function __construct(
        public TranslatableText $name,
        public ?string $email,
        public Phone $phone,
        public string $password,
        public RoleId $roleId,
        public ?UploadedFile $avatar,
    ) {}
}
