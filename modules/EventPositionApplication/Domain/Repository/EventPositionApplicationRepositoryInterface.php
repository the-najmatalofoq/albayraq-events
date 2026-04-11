<?php
// filePath: modules/EventPositionApplication/Domain/Repository/EventPositionApplicationRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Domain\Repository;

use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface EventPositionApplicationRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): ApplicationId;

    public function save(EventPositionApplication $application): void;

    public function findById(ApplicationId $id): ?EventPositionApplication;

    public function findByIdWithTrashed(ApplicationId $id): ?EventPositionApplication;

    public function findByUserId(UserId $userId): array;

    public function findByPositionId(PositionId $positionId): array;

    public function delete(ApplicationId $id): void;

    public function hardDelete(ApplicationId $id): void;

    public function restore(ApplicationId $id): void;
}
