<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\DeleteJoinRequest;

final readonly class DeleteJoinRequestCommand
{
    public function __construct(
        public string $joinRequestId,
    ) {}
}
