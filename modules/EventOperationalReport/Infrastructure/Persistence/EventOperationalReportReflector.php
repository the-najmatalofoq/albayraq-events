<?php
// modules/EventOperationalReport/Infrastructure/Persistence/EventOperationalReportReflector.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Infrastructure\Persistence;

use Modules\EventOperationalReport\Domain\EventOperationalReport;
use Modules\EventOperationalReport\Domain\ValueObject\ReportId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventOperationalReport\Domain\Enum\ReportStatus;
use Modules\EventOperationalReport\Infrastructure\Persistence\Eloquent\EventOperationalReportModel;

final class EventOperationalReportReflector
{
    public static function fromModel(EventOperationalReportModel $model): EventOperationalReport
    {
        $reflection = new \ReflectionClass(EventOperationalReport::class);
        $report = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ReportId::fromString($model->id),
            'eventId' => EventId::fromString($model->event_id),
            'reportTypeId' => $model->report_type_id,
            'content' => TranslatableText::fromArray($model->content),
            'reportedBy' => UserId::fromString($model->reported_by),
            'status' => ReportStatus::from($model->status),
            'createdAt' => \DateTimeImmutable::createFromMutable($model->created_at),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($report, $value);
        }

        return $report;
    }
}
