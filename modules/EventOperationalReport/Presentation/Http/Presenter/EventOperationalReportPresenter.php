<?php
// modules/EventOperationalReport/Presentation/Http/Presenter/EventOperationalReportPresenter.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Presentation\Http\Presenter;

use Modules\EventOperationalReport\Domain\EventOperationalReport;

final class EventOperationalReportPresenter
{
    public static function fromDomain(EventOperationalReport $report): array
    {
        return [
            'id' => $report->uuid->value,
            'event_id' => $report->eventId->value,
            'report_type_id' => $report->reportTypeId,
            'content' => $report->content->toArray(),
            'reported_by' => $report->reportedBy->value,
            'status' => $report->status->value,
            'created_at' => $report->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
