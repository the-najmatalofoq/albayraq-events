<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateContactPhone;

use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Domain\ValueObject\UserId;

final readonly class UpdateContactPhoneCommand
{
    public function __construct(
        public UserId $userId,
        public string $name,
        public string $phone,
        public string $relation,
    ) {}
}
