<?php
// modules/Shared/Domain/ValueObject/GeoPoint.php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class GeoPoint extends ValueObject
{
    public function __construct(
        public float $latitude,
        public float $longitude
    ) {}

    public function distanceTo(GeoPoint $other): float
    {
        $earthRadius = 6371000;

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($other->latitude);
        $lon2 = deg2rad($other->longitude);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self
            && $this->latitude === $other->latitude
            && $this->longitude === $other->longitude;
    }
}
