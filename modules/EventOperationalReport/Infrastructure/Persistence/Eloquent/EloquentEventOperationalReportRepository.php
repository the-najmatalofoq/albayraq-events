<?php
// modules/EventOperationalReport/Infrastructure/Persistence/Eloquent/EloquentEventOperationalReportRepository.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Infrastructure\Persistence\Eloquent;

use Modules\EventOperationalReport\Domain\EventOperationalReport;
use Modules\EventOperationalReport\Domain\ValueObject\ReportId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventOperationalReport\Domain\Repository\EventOperationalReportRepositoryInterface;
use Modules\EventOperationalReport\Infrastructure\Persistence\EventOperationalReportReflector;

final class EloquentEventOperationalReportRepository implements EventOperationalReportRepositoryInterface
{
    public function nextIdentity(): ReportId
    {
        return ReportId::generate();
    }

    public function save(EventOperationalReport $report): void
    {
        EventOperationalReportModel::updateOrCreate(
            ['id' => $report->uuid->value],
            [
                'event_id' => $report->eventId->value,
                'report_type_id' => $report->reportTypeId,
                'content' => $report->content->toArray(),
                'reported_by' => $report->reportedBy->value,
                'status' => $report->status->value,
            ]
        );
    }

    public function findById(ReportId $id): ?EventOperationalReport
    {
        $model = EventOperationalReportModel::find($id->value);
        return $model ? EventOperationalReportReflector::fromModel($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventOperationalReportModel::where('event_id', $eventId->value)
            ->get()
            ->map(fn(EventOperationalReportModel $m) => EventOperationalReportReflector::fromModel($m))
            ->toArray();
    }
}
