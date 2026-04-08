<?php
// modules/ReportType/Application/Command/DeleteReportType/DeleteReportTypeCommand.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Command\DeleteReportType;

final readonly class DeleteReportTypeCommand
{
    public function __construct(
        public string $id
    ) {}
}
