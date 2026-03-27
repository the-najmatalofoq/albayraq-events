<?php
// modules/Shared/Presentation/Http/Presenter/GeoPointPresenter.php
declare(strict_types=1);

namespace Modules\Shared\Presentation\Http\Presenter;

use Modules\Shared\Domain\ValueObject\GeoPoint;

final class GeoPointPresenter
{
    public static function fromDomain(GeoPoint $location): array
    {
        return [
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ];
    }
}
