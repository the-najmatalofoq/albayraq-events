<?php
// modules/EventBreakRequest/Domain/BreakRequest.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Domain;

use Carbon\CarbonImmutable;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;

final class BreakRequest extends AggregateRoot
{
    private function __construct(
        public readonly BreakRequestId $uuid,
        public readonly ParticipationId $participationId,
        public readonly CarbonImmutable $date,
        public readonly CarbonImmutable $startTime,
        public readonly CarbonImmutable $endTime,
        public readonly int $durationMinutes,
        public private(set) BreakRequestStatus $status,
        public readonly UserId $requestedBy,
        public private(set) ?UserId $approvedBy = null,
        public private(set) ?CarbonImmutable $approvedAt = null,
        public private(set) ?string $rejectionReason = null,
        public private(set) ?UserId $coverEmployeeId = null,
        public readonly CarbonImmutable $createdAt = new CarbonImmutable(),
    ) {}

    public static function request(
        BreakRequestId $uuid,
        ParticipationId $participationId,
        CarbonImmutable $date,
        CarbonImmutable $startTime,
        CarbonImmutable $endTime,
        UserId $requestedBy
    ): self {
        $durationMinutes = (int) $startTime->diffInMinutes($endTime);
        
        return new self(
            uuid: $uuid,
            participationId: $participationId,
            date: $date,
            startTime: $startTime,
            endTime: $endTime,
            durationMinutes: $durationMinutes,
            status: BreakRequestStatus::PENDING,
            requestedBy: $requestedBy,
            createdAt: new CarbonImmutable()
        );
    }

    public function approve(UserId $approverId, ?UserId $coverEmployeeId = null): void
    {
        if ($this->status === BreakRequestStatus::APPROVED) {
            return; // Already approved
        }
        
        $this->status = BreakRequestStatus::APPROVED;
        $this->approvedBy = $approverId;
        $this->approvedAt = new CarbonImmutable();
        $this->coverEmployeeId = $coverEmployeeId;
    }

    public function reject(UserId $approverId, string $reason): void
    {
        if ($this->status !== BreakRequestStatus::PENDING) {
            throw new Exceptions\BreakRequestException("Only pending requests can be rejected.");
        }

        $this->status = BreakRequestStatus::REJECTED;
        $this->approvedBy = $approverId; // Use approvedBy playfully for the rejector as well
        $this->rejectionReason = $reason;
    }

    public function cancel(): void
    {
        if ($this->status === BreakRequestStatus::APPROVED) {
            throw new Exceptions\BreakRequestException("Cannot cancel an approved request.");
        }

        $this->status = BreakRequestStatus::CANCELLED;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
