<?php
// modules/EventShiftAssignment/Application/Command/CreateShiftAssignment/CreateShiftAssignmentHandler.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Application\Command\CreateShiftAssignment;

use Modules\EventShiftAssignment\Domain\EventShiftAssignment;
use Modules\EventShiftAssignment\Domain\Repository\EventShiftAssignmentRepositoryInterface;
use Modules\EventShiftAssignment\Domain\Exception\DuplicateShiftAssignmentException;
use Modules\EventShiftAssignment\Domain\Exception\InvalidShiftPositionException;
use Modules\EventShiftAssignment\Domain\Exception\ShiftCapacityExceededException;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;
use Modules\EventShift\Domain\Repository\EventShiftRepositoryInterface;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventParticipation\Domain\EventParticipation;
use Modules\EventShift\Domain\EventShift;

final readonly class CreateShiftAssignmentHandler
{
    public function __construct(
        private EventShiftAssignmentRepositoryInterface $assignmentRepository,
        private EventShiftRepositoryInterface $shiftRepository,
        private EventParticipationRepositoryInterface $participationRepository,
    ) {
    }

    public function handle(CreateShiftAssignmentCommand $command): ShiftAssignmentId
    {
        $participationId = ParticipationId::fromString($command->participationId);
        $shiftId = ShiftId::fromString($command->shiftId);

        $participation = $this->validateAndGetParticipation($participationId, $command->participationId);
        $shift = $this->validateAndGetShift($shiftId, $command->shiftId);

        $this->validateShiftPositionMatchesParticipation($shift, $participation, $command);
        $this->validateShiftBelongsToSameEvent($shift, $participation);
        $this->guardAgainstDuplicateAssignment($participationId, $shiftId, $command);
        $this->guardAgainstCapacityExceeded($shift, $shiftId, $command);

        return $this->createAndSaveAssignment($participationId, $shiftId);
    }

    private function validateAndGetParticipation(
        ParticipationId $participationId,
        string $participationIdString
    ): EventParticipation {
        $participation = $this->participationRepository->findById($participationId);
        if ($participation === null) {
            throw new \DomainException("Participation {$participationIdString} not found.");
        }

        return $participation;
    }

    private function validateAndGetShift(
        ShiftId $shiftId,
        string $shiftIdString
    ): EventShift {
        $shift = $this->shiftRepository->findById($shiftId);
        if ($shift === null) {
            throw new \DomainException("Shift {$shiftIdString} not found.");
        }

        return $shift;
    }

    private function validateShiftPositionMatchesParticipation(
        EventShift $shift,
        EventParticipation $participation,
        CreateShiftAssignmentCommand $command
    ): void {
        if ($shift->positionId->value !== $participation->positionId->value) {
            throw InvalidShiftPositionException::create($command->shiftId, $command->participationId);
        }
    }

    private function validateShiftBelongsToSameEvent(
        EventShift $shift,
        EventParticipation $participation
    ): void {
        if ($shift->eventId->value !== $participation->eventId->value) {
            throw new \DomainException("Shift does not belong to the same event as the participation.");
        }
    }

    private function guardAgainstDuplicateAssignment(
        ParticipationId $participationId,
        ShiftId $shiftId,
        CreateShiftAssignmentCommand $command
    ): void {
        $existing = $this->assignmentRepository->findByParticipationAndShift($participationId, $shiftId);
        if ($existing !== null && $existing->isActive()) {
            throw DuplicateShiftAssignmentException::create($command->participationId, $command->shiftId);
        }
    }

    private function guardAgainstCapacityExceeded(
        EventShift $shift,
        ShiftId $shiftId,
        CreateShiftAssignmentCommand $command
    ): void {
        if ($shift->maxAssignees !== null) {
            $activeCount = $this->assignmentRepository->countActiveByShiftId($shiftId);
            if ($activeCount >= $shift->maxAssignees) {
                throw ShiftCapacityExceededException::create($command->shiftId);
            }
        }
    }

    private function createAndSaveAssignment(
        ParticipationId $participationId,
        ShiftId $shiftId
    ): ShiftAssignmentId {
        $id = $this->assignmentRepository->nextIdentity();
        $assignment = EventShiftAssignment::create($id, $participationId, $shiftId);
        $this->assignmentRepository->save($assignment);

        return $id;
    }
}