<?php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Services;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Service\DomainEventDispatcher;

final readonly class LaravelDomainEventDispatcher implements DomainEventDispatcher
{
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            event($event);
        }
    }

    public function dispatchFrom(AggregateRoot $aggregateRoot): void
    {
        $this->dispatch($aggregateRoot->pullDomainEvents());
    }
}
