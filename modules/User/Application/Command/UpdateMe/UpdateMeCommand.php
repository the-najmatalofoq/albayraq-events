<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\UpdateMe;

final readonly class UpdateMeCommand
{
    public function __construct(
        public string $userId,
        public string $name,
        public string $phone,
    ) {}
}
