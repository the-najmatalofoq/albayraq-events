<?php
declare(strict_types=1);

namespace Modules\Shared\Domain\Service;

use Modules\Shared\Domain\AggregateRoot;

interface DomainEventDispatcher
{
    public function dispatch(array $events): void;

    public function dispatchFrom(AggregateRoot $aggregateRoot): void;
}
