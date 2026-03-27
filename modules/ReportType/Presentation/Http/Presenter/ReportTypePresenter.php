<?php
// modules/ReportType/Presentation/Http/Presenter/ReportTypePresenter.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Presenter;

use Modules\ReportType\Domain\ReportType;

final class ReportTypePresenter
{
    public static function fromDomain(ReportType $reportType): array
    {
        return [
            'id' => $reportType->uuid->value,
            'name' => $reportType->name->toArray(),
            'code' => $reportType->code,
            'is_active' => $reportType->isActive,
        ];
    }
}
