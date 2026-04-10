<?php
// modules/EventJoinRequest/Application/Command/CreateJoinRequest/CreateJoinRequestCommand.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Application\Command\CreateJoinRequest;

final readonly class CreateJoinRequestCommand
{
    public function __construct(
        public string $userId,
        public string $eventId,
        public string $positionId,
    ) {}
}
