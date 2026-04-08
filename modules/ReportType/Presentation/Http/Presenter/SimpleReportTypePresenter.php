<?php
// modules/ReportType/Presentation/Http/Presenter/SimpleReportTypePresenter.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Presenter;

use Modules\ReportType\Domain\ReportType;

final class SimpleReportTypePresenter
{
    public static function fromDomain(ReportType $reportType): array
    {
        return [
            'id' => $reportType->uuid->value,
            'name' => $reportType->name->getFor(),
        ];
    }
}
