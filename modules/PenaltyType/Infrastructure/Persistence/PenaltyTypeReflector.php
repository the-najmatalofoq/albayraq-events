<?php
// modules/PenaltyType/Infrastructure/Persistence/PenaltyTypeReflector.php
declare(strict_types=1);

namespace Modules\PenaltyType\Infrastructure\Persistence;

use Modules\PenaltyType\Domain\PenaltyType;
use Modules\PenaltyType\Infrastructure\Persistence\Eloquent\PenaltyTypeModel;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;

final class PenaltyTypeReflector
{
    public static function fromModel(PenaltyTypeModel $model): PenaltyType
    {
        return PenaltyType::create(
            uuid: PenaltyTypeId::fromString($model->id),
            slug: $model->slug,
            name: $model->name,
            isActive: (bool) $model->is_active
        );
    }
}
