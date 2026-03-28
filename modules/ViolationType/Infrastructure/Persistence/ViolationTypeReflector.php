<?php
// modules/ViolationType/Infrastructure/Persistence/ViolationTypeReflector.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;

final class ViolationTypeReflector
{
    public static function fromModel(ViolationTypeModel $model): ViolationType
    {
        $reflection = new \ReflectionClass(ViolationType::class);
        $violationType = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => ViolationTypeId::fromString($model->id),
            'name'              => TranslatableText::fromArray($model->name),
            'defaultDeduction'  => $model->default_deduction_amount ? new Money((float)$model->default_deduction_amount, $model->default_deduction_currency) : null,
            'severity'          => ViolationSeverityEnum::from($model->severity),
            'isActive'          => (bool) $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($violationType, $value);
        }

        return $violationType;
    }
}
