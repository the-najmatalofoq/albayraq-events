<?php
// modules/EventAttendance/Presentation/Http/Presenter/EventAttendancePresenter.php
declare(strict_types=1);

namespace Modules\EventAttendance\Presentation\Http\Presenter;

use Modules\EventAttendance\Domain\EventAttendance;
use Modules\EventParticipation\Presentation\Http\Presenter\EventParticipationPresenter;
use Modules\EventParticipation\Domain\EventParticipation;

final class EventAttendancePresenter
{
    public function present(EventAttendance $attendance, ?EventParticipation $participation = null): array
    {
        return [
            'uuid'              => $attendance->uuid->value,
            'participation_id'   => $attendance->participationId->value,
            'check_in'          => [
                'time'      => $attendance->checkIn?->format(DATE_ATOM),
                'latitude'  => $attendance->checkInLatitude,
                'longitude' => $attendance->checkInLongitude,
            ],
            'check_out'         => [
                'time'      => $attendance->checkOut?->format(DATE_ATOM),
                'latitude'  => $attendance->checkOutLatitude,
                'longitude' => $attendance->checkOutLongitude,
            ],
            'method'            => $attendance->method->value,
            'status'            => [
                'is_verified' => $attendance->isVerified(),
                'verified_by' => $attendance->verifiedBy?->value,
            ],
            'participation'     => $participation ? EventParticipationPresenter::fromDomain($participation) : null,
        ];
    }

    public function presentCollection(iterable $attendances): array
    {
        $data = [];
        foreach ($attendances as $attendance) {
            $data[] = $this->present($attendance);
        }
        return $data;
    }
}
