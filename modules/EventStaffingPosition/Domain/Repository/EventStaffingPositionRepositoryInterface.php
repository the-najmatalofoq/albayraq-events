<?php
// modules/EventStaffingPosition/Domain/Repository/EventStaffingPositionRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventStaffingPosition\Domain\Repository;

use Modules\EventStaffingPosition\Domain\EventStaffingPosition;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Event\Domain\ValueObject\EventId;

interface EventStaffingPositionRepositoryInterface
{
    public function nextIdentity(): PositionId;

    public function save(EventStaffingPosition $position): void;

    public function findById(PositionId $id): ?EventStaffingPosition;

    public function findByEventId(EventId $eventId): array;
}
