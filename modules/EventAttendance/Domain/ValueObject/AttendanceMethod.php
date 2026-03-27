<?php
// modules/EventAttendance/Domain/ValueObject/AttendanceMethod.php
declare(strict_types=1);

namespace Modules\EventAttendance\Domain\ValueObject;

enum AttendanceMethod: string
{
    case APP = 'app';
    case QR = 'qr';
    case MANUAL = 'manual';
    case NFC = 'nfc';

    public function label(): string
    {
        return match ($this) {
            self::APP => 'Mobile App',
            self::QR => 'QR Scan',
            self::MANUAL => 'Manual Entry',
            self::NFC => 'NFC Tag',
        };
    }
}
