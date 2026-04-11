<?php
// modules/EventRoleCapability/Domain/Repository/EventRoleCapabilityRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Domain\Repository;

use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;

use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface EventRoleCapabilityRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): CapabilityId;

    public function save(EventRoleCapability $capability): void;

    public function findById(CapabilityId $id): ?EventRoleCapability;

    public function findByIdWithTrashed(CapabilityId $id): ?EventRoleCapability;

    public function findByAssignmentId(AssignmentId $assignmentId): array;

    public function delete(CapabilityId $id): void;

    public function hardDelete(CapabilityId $id): void;

    public function restore(CapabilityId $id): void;
}
