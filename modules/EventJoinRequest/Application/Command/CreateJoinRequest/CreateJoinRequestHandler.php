<?php
// modules/EventJoinRequest/Application/Command/CreateJoinRequest/CreateJoinRequestHandler.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Application\Command\CreateJoinRequest;

use Modules\EventJoinRequest\Domain\EventJoinRequest;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Domain\Exception\DuplicateJoinRequestException;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final readonly class CreateJoinRequestHandler
{
    public function __construct(
        private EventJoinRequestRepositoryInterface $repository,
    ) {}

    public function handle(CreateJoinRequestCommand $command): JoinRequestId
    {
        $userId = UserId::fromString($command->userId);
        $eventId = EventId::fromString($command->eventId);

        $existing = $this->repository->findByUserAndEvent($userId, $eventId);
        if ($existing !== null) {
            throw DuplicateJoinRequestException::create("user={$command->userId}, event={$command->eventId}");
        }

        $id = $this->repository->nextIdentity();

        $request = EventJoinRequest::create(
            uuid: $id,
            userId: $userId,
            eventId: $eventId,
            positionId: PositionId::fromString($command->positionId),
        );

        $this->repository->save($request);

        return $id;
    }
}
