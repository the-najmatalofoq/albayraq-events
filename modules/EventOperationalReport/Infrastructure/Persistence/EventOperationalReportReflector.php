<?php
// modules/EventOperationalReport/Infrastructure/Persistence/EventOperationalReportReflector.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Infrastructure\Persistence;

use Modules\EventOperationalReport\Domain\EventOperationalReport;
use Modules\EventOperationalReport\Domain\ValueObject\ReportId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventOperationalReport\Domain\Enum\ReportStatusEnum;
use Modules\EventOperationalReport\Infrastructure\Persistence\Eloquent\EventOperationalReportModel;
use Modules\User\Domain\ValueObject\UserId;

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
            'title' => $model->title ? TranslatableText::fromArray($model->title) : null,
            'content' => TranslatableText::fromArray($model->content),
            'date' => \DateTimeImmutable::createFromMutable($model->date),
            'authorId' => UserId::fromString($model->author_id),
            'status' => ReportStatusEnum::from($model->status),
            'approvedBy' => $model->approved_by ? UserId::fromString($model->approved_by) : null,
            'approvedAt' => $model->approved_at ? \DateTimeImmutable::createFromMutable($model->approved_at) : null,
            'createdAt' => \DateTimeImmutable::createFromMutable($model->created_at),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($report, $value);
        }

        return $report;
    }
}
