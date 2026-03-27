<?php

declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Messaging;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Shared\Application\EventDispatcher as EventDispatcherInterface;

final readonly class LaravelEventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
    ) {}

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
