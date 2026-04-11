<?php
// modules/EventRoleCapability/Domain/Repository/EventRoleCapabilityRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Domain\Repository;

use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface EventRoleCapabilityRepositoryInterface
{
    public function nextIdentity(): CapabilityId;

    public function save(EventRoleCapability $capability): void;

    public function findById(CapabilityId $id): ?EventRoleCapability;

    public function findByAssignmentId(AssignmentId $assignmentId): array;
}
