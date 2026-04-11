<?php
// modules/EventShift/Domain/Repository/EventShiftRepositoryInterface.php
declare(strict_types=1);
namespace Modules\EventShift\Domain\Repository;

use Modules\EventShift\Domain\EventShift;
use Modules\EventShift\Domain\ValueObject\ShiftId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\User\Domain\ValueObject\UserId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventShiftRepositoryInterface
{
    public function nextIdentity(): ShiftId;
    public function save(EventShift $shift): void;
    public function findById(ShiftId $id): ?EventShift;
    public function findByEventId(EventId $eventId): array;
    public function findByEventAndPosition(EventId $eventId, PositionId $positionId): array;
    public function findOverlapping(EventId $eventId, PositionId $positionId, \DateTimeImmutable $startAt, \DateTimeImmutable $endAt, ?ShiftId $excludeId = null): array;
    /** Returns all active EventShift records assigned to a user via active participations. */
    public function findActiveByUserId(UserId $userId): array;
    public function delete(ShiftId $id): void;
}