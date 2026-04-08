<?php
// modules/ReportType/Application/Command/CreateReportType/CreateReportTypeCommand.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Command\CreateReportType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateReportTypeCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $slug,
        public bool $isActive = true
    ) {}
}
