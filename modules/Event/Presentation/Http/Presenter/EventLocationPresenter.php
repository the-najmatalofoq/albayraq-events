<?php

declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Presenter;

use Modules\Event\Domain\Event;

final class EventLocationPresenter
{
    public static function present(Event $event): array
    {
        return [
            'latitude' => $event->latitude,
            'longitude' => $event->longitude,
            'radius' => $event->geofenceRadius,
            'address' => $event->address,
        ];
    }
}
