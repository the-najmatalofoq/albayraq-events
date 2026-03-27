<?php
// modules/EventOperationalReport/Domain/Repository/EventOperationalReportRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Domain\Repository;

use Modules\EventOperationalReport\Domain\EventOperationalReport;
use Modules\EventOperationalReport\Domain\ValueObject\ReportId;
use Modules\Event\Domain\ValueObject\EventId;

interface EventOperationalReportRepositoryInterface
{
    public function nextIdentity(): ReportId;

    public function save(EventOperationalReport $report): void;

    public function findById(ReportId $id): ?EventOperationalReport;

    /** @return EventOperationalReport[] */
    public function findByEventId(EventId $eventId): array;
}
