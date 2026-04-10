<?php
// modules/EventShiftAssignment/Application/Command/AssignToShift/AssignToShiftHandler.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Application\Command\AssignToShift;

use Modules\EventShiftAssignment\Domain\EventShiftAssignment;
use Modules\EventShiftAssignment\Domain\Repository\EventShiftAssignmentRepositoryInterface;
use Modules\EventShiftAssignment\Domain\Exception\PositionMismatchException;
use Modules\EventShiftAssignment\Domain\Exception\ShiftFullException;
use Modules\EventShiftAssignment\Domain\Exception\ShiftAssignmentNotFoundException;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\Exception\ShiftNotFoundException;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;

final readonly class AssignToShiftHandler
{
    public function __construct(
        private EventShiftAssignmentRepositoryInterface $assignmentRepository,
        private EventShiftRepositoryInterface $shiftRepository,
        private EventParticipationRepositoryInterface $participationRepository,
    ) {}

    public function handle(AssignToShiftCommand $command): ShiftAssignmentId
    {
        $shiftId = ShiftId::fromString($command->shiftId);
        $participationId = ParticipationId::fromString($command->participationId);

        $shift = $this->shiftRepository->findById($shiftId);
        if ($shift === null) {
            throw ShiftNotFoundException::create($command->shiftId);
        }

        $participation = $this->participationRepository->findById($participationId);
        if ($participation === null) {
            throw ShiftAssignmentNotFoundException::create($command->participationId);
        }

        if ($shift->positionId->value !== $participation->positionId->value) {
            throw PositionMismatchException::create(
                "shift_position={$shift->positionId->value}, participation_position={$participation->positionId->value}"
            );
        }

        if ($shift->maxAssignees !== null) {
            $currentCount = $this->assignmentRepository->countActiveByShiftId($shiftId);
            if ($currentCount >= $shift->maxAssignees) {
                throw ShiftFullException::create("{$command->shiftId} (max: {$shift->maxAssignees})");
            }
        }

        $id = $this->assignmentRepository->nextIdentity();

        $assignment = EventShiftAssignment::create(
            uuid: $id,
            shiftId: $shiftId,
            participationId: $participationId,
            assignedBy: UserId::fromString($command->assignedBy),
            notes: $command->notes,
        );

        $this->assignmentRepository->save($assignment);

        return $id;
    }
}
