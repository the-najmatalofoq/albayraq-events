<?php
// modules/ReportType/Application/Command/DeleteReportType/DeleteReportTypeHandler.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Command\DeleteReportType;

use Modules\ReportType\Domain\Exception\ReportTypeNotFoundException;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;

final readonly class DeleteReportTypeHandler
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository
    ) {}

    public function handle(DeleteReportTypeCommand $command): void
    {
        $id = ReportTypeId::fromString($command->id);
        $reportType = $this->repository->findById($id);

        if (!$reportType) {
            throw ReportTypeNotFoundException::withId($id);
        }

        $this->repository->delete($id);
    }
}
