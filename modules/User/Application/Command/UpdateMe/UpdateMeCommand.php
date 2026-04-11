<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateMe;

use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateMeCommand
{
    public function __construct(
        public UserId $userId,
        public TranslatableText $name,
        public Phone $phone,
    ) {}
}
