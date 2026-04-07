<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateContactPhone;

final readonly class UpdateContactPhoneCommand
{
    public function __construct(
        public string $userId,
        public string $contactPhoneId,
        public string $name,
        public string $phone,
        public string $relation,
    ) {}
}
