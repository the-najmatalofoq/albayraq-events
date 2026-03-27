<?php
// modules/EventStaffingGroup/Domain/Repository/EventStaffingGroupRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Domain\Repository;

use Modules\EventStaffingGroup\Domain\EventStaffingGroup;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Event\Domain\ValueObject\EventId;

interface EventStaffingGroupRepositoryInterface
{
    public function nextIdentity(): GroupId;

    public function save(EventStaffingGroup $group): void;

    public function findById(GroupId $id): ?EventStaffingGroup;

    public function findByEventId(EventId $eventId): array;
}
