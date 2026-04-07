<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateEmail;

final readonly class UpdateEmailCommand
{
    public function __construct(
        public string $userId,
        public string $email,
    ) {}
}
