<?php
// modules/EventOperationalReport/Domain/EventOperationalReport.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventOperationalReport\Domain\ValueObject\ReportId;
use Modules\EventOperationalReport\Domain\Enum\ReportStatusEnum;
use Modules\User\Domain\ValueObject\UserId;

final class EventOperationalReport extends AggregateRoot
{
    public function __construct(
        public readonly ReportId $uuid,
        public readonly EventId $eventId,
        public readonly string $reportTypeId,
        public readonly ?TranslatableText $title,
        public readonly TranslatableText $content,
        public readonly \DateTimeImmutable $date,
        public readonly UserId $authorId,
        public private(set) ReportStatusEnum $status = ReportStatusEnum::DRAFT,
        public private(set) ?UserId $approvedBy = null,
        public private(set) ?\DateTimeImmutable $approvedAt = null,
        public readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {
    }

    public static function create(
        ReportId $uuid,
        EventId $eventId,
        string $reportTypeId,
        ?TranslatableText $title,
        TranslatableText $content,
        \DateTimeImmutable $date,
        UserId $authorId
    ): self {
        return new self($uuid, $eventId, $reportTypeId, $title, $content, $date, $authorId);
    }

    public function submit(): void
    {
        $this->status = ReportStatusEnum::SUBMITTED;
    }

    public function approve(UserId $approverId): void
    {
        $this->status = ReportStatusEnum::APPROVED;
        $this->approvedBy = $approverId;
        $this->approvedAt = new \DateTimeImmutable();
    }

    public function reject(): void
    {
        $this->status = ReportStatusEnum::REJECTED;
        $this->approvedBy = null;
        $this->approvedAt = null;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
