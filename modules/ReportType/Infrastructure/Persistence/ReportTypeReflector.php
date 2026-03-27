<?php
// modules/ReportType/Infrastructure/Persistence/ReportTypeReflector.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence;

use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ReportType\Infrastructure\Persistence\Eloquent\ReportTypeModel;

final class ReportTypeReflector
{
    public static function fromModel(ReportTypeModel $model): ReportType
    {
        $reflection = new \ReflectionClass(ReportType::class);
        $reportType = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ReportTypeId::fromString($model->id),
            'name' => TranslatableText::fromArray($model->name),
            'code' => $model->code,
            'isActive' => $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($reportType, $value);
        }

        return $reportType;
    }
}
