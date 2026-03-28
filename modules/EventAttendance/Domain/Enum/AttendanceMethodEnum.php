<?php
// modules/EventAttendance/Domain/Enum/AttendanceMethodEnum.php
declare(strict_types=1);

namespace Modules\EventAttendance\Domain\Enum;

enum AttendanceMethodEnum: string
{
    case BARCODE = 'barcode';
    case MANUAL = 'manual';
    case GPS = 'gps';
    case NFC = 'nfc';
}
