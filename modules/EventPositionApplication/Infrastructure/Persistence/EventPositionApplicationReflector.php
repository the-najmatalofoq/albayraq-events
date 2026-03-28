<?php
// modules/EventPositionApplication/Infrastructure/Persistence/EventPositionApplicationReflector.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Persistence;

use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationStatusEnum;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent\EventPositionApplicationModel;
use DateTimeImmutable;

final class EventPositionApplicationReflector
{
    public static function fromModel(EventPositionApplicationModel $model): EventPositionApplication
    {
        $reflection = new \ReflectionClass(EventPositionApplication::class);
        $application = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'          => ApplicationId::fromString($model->id),
            'userId'        => UserId::fromString($model->user_id),
            'positionId'    => PositionId::fromString($model->position_id),
            'status'        => ApplicationStatusEnum::from($model->status),
            'rankingScore'  => (float) $model->ranking_score,
            'appliedAt'     => DateTimeImmutable::createFromInterface($model->applied_at),
            'reviewedAt'    => $model->reviewed_at ? DateTimeImmutable::createFromInterface($model->reviewed_at) : null,
            'reviewedBy'    => $model->reviewed_by ? UserId::fromString($model->reviewed_by) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($application, $value);
        }

        return $application;
    }
}
