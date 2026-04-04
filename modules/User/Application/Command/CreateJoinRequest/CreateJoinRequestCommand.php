<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\CreateJoinRequest;

final readonly class CreateJoinRequestCommand
{
    public function __construct(
        public string $userId,
    ) {}
}
