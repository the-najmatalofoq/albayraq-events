<?php
// modules/EventAttendance/Domain/Repository/EventAttendanceRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventAttendance\Domain\Repository;

use Modules\EventAttendance\Domain\EventAttendance;
use Modules\EventAttendance\Domain\ValueObject\AttendanceId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;

interface EventAttendanceRepositoryInterface
{
    public function nextIdentity(): AttendanceId;

    public function save(EventAttendance $attendance): void;

    public function findById(AttendanceId $id): ?EventAttendance;

    public function findByParticipationId(ParticipationId $participationId): array;
}
