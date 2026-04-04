<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Listener;

use Modules\IAM\Domain\Event\UserRegistered;
use Modules\User\Application\Command\CreateJoinRequest\CreateJoinRequestCommand;
use Modules\User\Application\Command\CreateJoinRequest\CreateJoinRequestHandler;

final readonly class CreateJoinRequestOnUserRegistered
{
    public function __construct(
        private CreateJoinRequestHandler $handler,
    ) {}

    public function handle(UserRegistered $event): void
    {
        $this->handler->handle(
            new CreateJoinRequestCommand(userId: $event->userId->value)
        );
    }
}
