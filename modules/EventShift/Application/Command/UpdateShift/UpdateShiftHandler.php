<?php
// modules/EventShift/Application/Command/UpdateShift/UpdateShiftHandler.php
declare(strict_types=1);

namespace Modules\EventShift\Application\Command\UpdateShift;

use DateTimeImmutable;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\Exception\ShiftTimeOverlapException;
use Modules\EventShift\Domain\ValueObject\ShiftId;

final readonly class UpdateShiftHandler
{
    public function __construct(private EventShiftRepositoryInterface $repository)
    {
    }

    public function handle(UpdateShiftCommand $command): void
    {
        $shiftId = ShiftId::fromString($command->shiftId);
        $shift = $this->repository->findById($shiftId)
            ?? throw new \DomainException("Shift {$command->shiftId} not found.");

        $startAt = $command->startAt ? new DateTimeImmutable($command->startAt) : $shift->startAt;
        $endAt = $command->endAt ? new DateTimeImmutable($command->endAt) : $shift->endAt;

        $overlapping = $this->repository->findOverlapping(
            $shift->eventId,
            $shift->positionId,
            $startAt,
            $endAt,
            $shiftId
        );

        if (count($overlapping) > 0) {
            throw ShiftTimeOverlapException::create($shift->positionId->value);
        }

        $shift->update(
            label: $command->label ?? $shift->label,
            startAt: $startAt,
            endAt: $endAt,
            maxAssignees: $command->maxAssignees ?? $shift->maxAssignees,
        );

        $this->repository->save($shift);
    }
}
