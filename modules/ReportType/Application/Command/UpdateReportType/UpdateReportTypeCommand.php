<?php
// modules/ReportType/Application/Command/UpdateReportType/UpdateReportTypeCommand.php
declare(strict_types=1);

namespace Modules\ReportType\Application\Command\UpdateReportType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateReportTypeCommand
{
    public function __construct(
        public string $id,
        public ?string $slug = null,
        public ?TranslatableText $name = null,
        public ?bool $isActive = null
    ) {}
}
