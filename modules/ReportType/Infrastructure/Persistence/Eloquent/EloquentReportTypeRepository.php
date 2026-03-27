<?php
// modules/ReportType/Infrastructure/Persistence/Eloquent/EloquentReportTypeRepository.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence\Eloquent;

use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Infrastructure\Persistence\ReportTypeReflector;

final class EloquentReportTypeRepository implements ReportTypeRepositoryInterface
{
    public function nextIdentity(): ReportTypeId
    {
        return ReportTypeId::generate();
    }

    public function save(ReportType $reportType): void
    {
        ReportTypeModel::updateOrCreate(
            ['id' => $reportType->uuid->value],
            [
                'name' => $reportType->name->toArray(),
                'code' => $reportType->code,
                'is_active' => $reportType->isActive,
            ]
        );
    }

    public function findById(ReportTypeId $id): ?ReportType
    {
        $model = ReportTypeModel::find($id->value);
        return $model ? ReportTypeReflector::fromModel($model) : null;
    }

    public function findByCode(string $code): ?ReportType
    {
        $model = ReportTypeModel::where('code', $code)->first();
        return $model ? ReportTypeReflector::fromModel($model) : null;
    }

    public function listAll(): array
    {
        return ReportTypeModel::all()
            ->map(fn($model) => ReportTypeReflector::fromModel($model))
            ->toArray();
    }
}
