<?php
// modules/ViolationType/Infrastructure/Persistence/ViolationTypeReflector.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;

final class ViolationTypeReflector
{
    public static function fromModel(ViolationTypeModel $model): ViolationType
    {
        return ViolationType::create(
            uuid: ViolationTypeId::fromString($model->id),
            slug: $model->slug,
            name: $model->name, // Already cast to TranslatableText in Model
            isActive: (bool) $model->is_active
        );
    }
}
