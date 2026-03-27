<?php
// modules/EventStaffingPositionRequirement/Domain/Repository/EventStaffingPositionRequirementRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Domain\Repository;

use Modules\EventStaffingPositionRequirement\Domain\EventStaffingPositionRequirement;
use Modules\EventStaffingPositionRequirement\Domain\ValueObject\RequirementId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

interface EventStaffingPositionRequirementRepositoryInterface
{
    public function nextIdentity(): RequirementId;

    public function save(EventStaffingPositionRequirement $requirement): void;

    public function findById(RequirementId $id): ?EventStaffingPositionRequirement;

    public function findByPositionId(PositionId $positionId): array;
}
