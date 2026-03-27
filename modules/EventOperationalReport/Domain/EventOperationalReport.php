<?php
// modules/EventOperationalReport/Domain/EventOperationalReport.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventOperationalReport\Domain\ValueObject\ReportId;
use Modules\EventOperationalReport\Domain\Enum\ReportStatus;
use Modules\IAM\Domain\ValueObject\UserId;

final class EventOperationalReport extends AggregateRoot
{
    public function __construct(
        public readonly ReportId $uuid,
        public readonly EventId $eventId,
        public readonly string $reportTypeId, // e.g. DAILY, FINAL
        public readonly TranslatableText $content,
        public readonly UserId $reportedBy,
        public private(set) ReportStatus $status = ReportStatus::DRAFT,
        public readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {}

    public static function create(
        ReportId $uuid,
        EventId $eventId,
        string $reportTypeId,
        TranslatableText $content,
        UserId $reportedBy
    ): self {
        return new self($uuid, $eventId, $reportTypeId, $content, $reportedBy);
    }

    public function submit(): void
    {
        $this->status = ReportStatus::SUBMITTED;
    }

    public function approve(): void
    {
        $this->status = ReportStatus::APPROVED;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
