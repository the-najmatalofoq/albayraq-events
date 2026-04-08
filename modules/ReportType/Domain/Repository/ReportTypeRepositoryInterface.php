<?php
// modules/ReportType/Domain/Repository/ReportTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ReportType\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

interface ReportTypeRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): ReportTypeId;

    public function save(ReportType $reportType): void;

    public function findById(ReportTypeId $id): ?ReportType;

    public function findBySlug(string $slug): ?ReportType;

    public function listAll(): array;

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator;

    public function all(FilterCriteria $criteria): Collection;

    public function delete(ReportTypeId $id): void;
}
