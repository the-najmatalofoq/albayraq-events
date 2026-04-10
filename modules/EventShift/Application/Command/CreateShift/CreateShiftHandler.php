<?php
// modules/EventShift/Application/Command/CreateShift/CreateShiftHandler.php
declare(strict_types=1);

namespace Modules\EventShift\Application\Command\CreateShift;

use DateTimeImmutable;
use Modules\EventShift\Domain\EventShift;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\Exception\ShiftTimeOverlapException;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final readonly class CreateShiftHandler
{
    public function __construct(
        private EventShiftRepositoryInterface $repository,
    ) {}

    public function handle(CreateShiftCommand $command): ShiftId
    {
        $eventId = EventId::fromString($command->eventId);
        $positionId = PositionId::fromString($command->positionId);
        $startAt = new DateTimeImmutable($command->startAt);
        $endAt = new DateTimeImmutable($command->endAt);

        $overlapping = $this->repository->findOverlapping($eventId, $positionId, $startAt, $endAt);
        if (count($overlapping) > 0) {
            throw ShiftTimeOverlapException::create($command->positionId);
        }

        $id = $this->repository->nextIdentity();

        $shift = EventShift::create(
            uuid: $id,
            eventId: $eventId,
            positionId: $positionId,
            label: $command->label,
            startAt: $startAt,
            endAt: $endAt,
            maxAssignees: $command->maxAssignees,
        );

        $this->repository->save($shift);

        return $id;
    }
}
