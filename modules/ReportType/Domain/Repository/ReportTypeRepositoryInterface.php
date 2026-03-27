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

    public function findByCode(string $code): ?ReportType;

    public function listAll(): array;
}
