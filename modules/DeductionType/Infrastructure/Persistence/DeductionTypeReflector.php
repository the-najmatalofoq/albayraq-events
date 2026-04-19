<?php
// modules/DeductionType/Infrastructure/Persistence/DeductionTypeReflector.php
declare(strict_types=1);

namespace Modules\DeductionType\Infrastructure\Persistence;

use Modules\DeductionType\Domain\DeductionType;
use Modules\DeductionType\Infrastructure\Persistence\Eloquent\DeductionTypeModel;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;

final class DeductionTypeReflector
{
    public static function fromModel(DeductionTypeModel $model): DeductionType
    {
        return DeductionType::create(
            uuid: DeductionTypeId::fromString($model->id),
            slug: $model->slug,
            name: $model->name,
            isActive: (bool) $model->is_active
        );
    }
}
