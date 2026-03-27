<?php
// modules/EventPositionApplication/Domain/Repository/EventPositionApplicationRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Domain\Repository;

use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

interface EventPositionApplicationRepositoryInterface
{
    public function nextIdentity(): ApplicationId;

    public function save(EventPositionApplication $application): void;

    public function findById(ApplicationId $id): ?EventPositionApplication;

    public function findByUserId(UserId $userId): array;

    public function findByPositionId(PositionId $positionId): array;
}
