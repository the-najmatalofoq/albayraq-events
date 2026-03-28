<?php
// modules/Shared/Infrastructure/Persistence/WageReflector.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Modules\Shared\Domain\Wage;
use Modules\Shared\Domain\ValueObject\WageId;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Shared\Infrastructure\Persistence\Eloquent\WageModel;

final class WageReflector
{
    public static function fromModel(WageModel $model): Wage
    {
        $reflection = new \ReflectionClass(Wage::class);
        $wage = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'          => WageId::fromString($model->id),
            'wageableId'    => $model->wageable_id,
            'wageableType'  => $model->wageable_type,
            'amount'        => new Money((float)$model->amount, $model->currency),
            'period'        => $model->period,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($wage, $value);
        }

        return $wage;
    }
}
