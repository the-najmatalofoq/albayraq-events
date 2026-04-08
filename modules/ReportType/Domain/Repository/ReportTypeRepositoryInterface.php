<?php
// modules/ReportType/Domain/Repository/ReportTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ReportType\Domain\Repository;

use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;

interface ReportTypeRepositoryInterface
{
    public function nextIdentity(): ReportTypeId;

    public function save(ReportType $reportType): void;

    public function findById(ReportTypeId $id): ?ReportType;

    public function findBySlug(string $slug): ?ReportType;

    public function listAll(): array;
    /**
     * @return array{items: ReportType[], total: int}
     */
    public function paginate(
        \Modules\Shared\Domain\ValueObject\PaginationCriteria $criteria,
        ?string $search = null,
        ?bool $isActive = null
    ): array;
    public function delete(ReportTypeId $id): void;
}
