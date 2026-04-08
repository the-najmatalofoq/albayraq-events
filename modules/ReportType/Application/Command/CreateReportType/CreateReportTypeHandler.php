<?php
// modules/ReportType/Application/Command/CreateReportType/CreateReportTypeHandler.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Command\CreateReportType;

use Modules\ReportType\Domain\ReportType;
use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateReportTypeHandler
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository
    ) {}

    public function handle(CreateReportTypeCommand $command): ReportTypeId
    {
        $id = $this->repository->nextIdentity();
        $reportType = ReportType::create(
            uuid: $id,
            name: $command->name,
            slug: $command->slug,
            isActive: $command->isActive
        );

        $this->repository->save($reportType);

        return $id;
    }
}
