<?php
// modules/ReportType/Infrastructure/Persistence/Eloquent/EloquentReportTypeRepository.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;
use Modules\ReportType\Infrastructure\Persistence\ReportTypeReflector;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
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

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = ReportTypeModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(fn(ReportTypeModel $model) => ReportTypeReflector::fromModel($model));

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = ReportTypeModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(ReportTypeModel $model) => ReportTypeReflector::fromModel($model));
    }

    public function delete(ReportTypeId $id): void
    {
        ReportTypeModel::destroy($id->value);
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where('name', 'like', "%{$criteria->search}%")
                  ->orWhere('slug', 'like', "%{$criteria->search}%");
        }

        if ($criteria->has('is_active')) {
            $query->where('is_active', (bool)$criteria->get('is_active'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }
}
