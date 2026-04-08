<?php
// modules/ReportType/Application/Command/UpdateReportType/UpdateReportTypeHandler.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Command\UpdateReportType;

use Modules\ReportType\Domain\Exception\ReportTypeNotFoundException;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateReportTypeHandler
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository
    ) {}

    public function handle(UpdateReportTypeCommand $command): void
    {
        $id = ReportTypeId::fromString($command->id);
        $reportType = $this->repository->findById($id);

        if (!$reportType) {
            throw ReportTypeNotFoundException::withId($id);
        }

        $reportType->update(
            name: $command->name,
            slug: $command->slug,
            isActive: $command->isActive
        );

        $this->repository->save($reportType);
    }
}
