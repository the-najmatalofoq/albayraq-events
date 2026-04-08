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
            'slug' => $reportType->slug,
            'name' => $reportType->name->toArray(),
            'is_active' => $reportType->isActive,
        ];
    }
}
