<?php

declare(strict_types=1);

namespace Modules\EventContract\Domain\Events;

final class ContractSent
{
    public function __construct(
        public readonly string $userId,
        public readonly string $contractId,
        public readonly string $eventName
    ) {}
}
