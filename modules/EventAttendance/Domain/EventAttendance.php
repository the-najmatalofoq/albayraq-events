<?php
// modules/EventAttendance/Domain/EventAttendance.php
declare(strict_types=1);

namespace Modules\EventAttendance\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\GeoPoint;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventAttendance\Domain\ValueObject\AttendanceId;
use Modules\EventAttendance\Domain\ValueObject\AttendanceMethodEnum;
use Modules\User\Domain\ValueObject\UserId;

final class EventAttendance extends AggregateRoot
{
    public function __construct(
        public readonly AttendanceId $uuid,
        public readonly ParticipationId $participationId,
        public readonly \DateTimeImmutable $date,
        public readonly \DateTimeImmutable $checkInAt,
        public private(set) ?\DateTimeImmutable $checkOutAt = null,
        public private(set) ?GeoPoint $checkInLocation = null,
        public private(set) ?GeoPoint $checkOutLocation = null,
        public private(set) AttendanceMethodEnum $method = AttendanceMethodEnum::APP,
        public private(set) ?UserId $verifiedBy = null
    ) {
    }

    public static function clockIn(
        AttendanceId $uuid,
        ParticipationId $participationId,
        \DateTimeImmutable $checkInAt,
        ?GeoPoint $location = null,
        AttendanceMethodEnum $method = AttendanceMethodEnum::APP
    ): self {
        return new self(
            $uuid,
            $participationId,
            new \DateTimeImmutable($checkInAt->format('Y-m-d')),
            $checkInAt,
            null,
            $location,
            null,
            $method
        );
    }

    public function clockOut(
        \DateTimeImmutable $checkOutAt,
        ?GeoPoint $location = null
    ): void {
        $this->checkOutAt = $checkOutAt;
        $this->checkOutLocation = $location;
    }

    public function verify(UserId $verifierId): void
    {
        $this->verifiedBy = $verifierId;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
