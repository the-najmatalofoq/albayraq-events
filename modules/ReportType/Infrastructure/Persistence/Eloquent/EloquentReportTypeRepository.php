<?php
// modules/ReportType/Infrastructure/Persistence/Eloquent/EloquentReportTypeRepository.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence\Eloquent;

use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;

use Modules\ReportType\Infrastructure\Persistence\ReportTypeReflector;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;

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
                'slug' => $reportType->slug,
                'is_active' => $reportType->isActive,
            ]
        );
    }

    public function findById(ReportTypeId $id): ?ReportType
    {
        $model = ReportTypeModel::find($id->value);
        return $model ? ReportTypeReflector::fromModel($model) : null;
    }

    public function findBySlug(string $slug): ?ReportType
    {
        $model = ReportTypeModel::where('slug', $slug)->first();
        return $model ? ReportTypeReflector::fromModel($model) : null;
    }

    public function listAll(): array
    {
        return ReportTypeModel::all()
            ->map(fn($model) => ReportTypeReflector::fromModel($model))
            ->toArray();
    }

    public function paginate(
        PaginationCriteria $criteria,
        ?string $search = null,
        ?bool $isActive = null
    ): array {
        $query = ReportTypeModel::query();
        $total = $query->count();
        $items = $query->offset($criteria->offset())
            ->limit($criteria->perPage)
            ->get()
            ->map(fn($model) => ReportTypeReflector::fromModel($model))
            ->toArray();

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function delete(ReportTypeId $id): void
    {
        ReportTypeModel::destroy($id->value);
    }
}
