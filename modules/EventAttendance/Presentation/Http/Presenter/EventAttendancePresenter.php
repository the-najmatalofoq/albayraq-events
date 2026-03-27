<?php
// modules/EventAttendance/Presentation/Http/Presenter/EventAttendancePresenter.php
declare(strict_types=1);

namespace Modules\EventAttendance\Presentation\Http\Presenter;

use Modules\EventAttendance\Domain\EventAttendance;
use Modules\Shared\Presentation\Http\Presenter\GeoPointPresenter;

final class EventAttendancePresenter
{
    public static function fromDomain(EventAttendance $attendance): array
    {
        return [
            'id' => $attendance->uuid->value,
            'event_participation_id' => $attendance->participationId->value,
            'date' => $attendance->date->format('Y-m-d'),
            'check_in_at' => $attendance->checkInAt->format('Y-m-d H:i:s'),
            'check_out_at' => $attendance->checkOutAt?->format('Y-m-d H:i:s'),
            'check_in_location' => $attendance->checkInLocation
                ? GeoPointPresenter::fromDomain($attendance->checkInLocation)
                : null,
            'check_out_location' => $attendance->checkOutLocation
                ? GeoPointPresenter::fromDomain($attendance->checkOutLocation)
                : null,
            'method' => $attendance->method->value,
            'method_label' => $attendance->method->label(),
            'verified_by' => $attendance->verifiedBy?->value,
        ];
    }
}
