<?php
// modules/EventShiftAssignment/Domain/Repository/EventShiftAssignmentRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventShiftAssignment\Domain\Repository;

use Modules\EventShiftAssignment\Domain\EventShiftAssignment;
use Modules\EventShiftAssignment\Domain\ValueObject\ShiftAssignmentId;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventShiftAssignmentRepositoryInterface
{
    public function nextIdentity(): ShiftAssignmentId;
    public function save(EventShiftAssignment $assignment): void;
    public function findById(ShiftAssignmentId $id): ?EventShiftAssignment;
    public function findByShiftId(ShiftId $shiftId): array;
    public function findByParticipationId(ParticipationId $participationId): array;
    public function findByParticipationAndShift(ParticipationId $participationId, ShiftId $shiftId): ?EventShiftAssignment;
    public function countActiveByShiftId(ShiftId $shiftId): int;
}
