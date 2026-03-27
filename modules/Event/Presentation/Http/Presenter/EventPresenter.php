<?php
// modules/Event/Presentation/Http/Presenter/EventPresenter.php
declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Presenter;

use Modules\Event\Domain\Event;
use Modules\Shared\Presentation\Http\Presenter\GeoPointPresenter;
use Modules\Shared\Presentation\Http\Presenter\PricePresenter;

final class EventPresenter
{
    public static function fromDomain(Event $event): array
    {
        return [
            'id' => $event->uuid->value,
            'name' => $event->name->toArray(),
            'slug' => $event->slug,
            'description' => $event->description->toArray(),
            'type' => $event->type,
            'start_date' => $event->startDate->format('Y-m-d H:i:s'),
            'end_date' => $event->endDate->format('Y-m-d H:i:s'),
            'location' => GeoPointPresenter::fromDomain($event->location),
            'price' => PricePresenter::fromDomain($event->price),
            'status' => $event->status->value,
            'status_label' => $event->status->label(),
            'banner_id' => $event->bannerId,
        ];
    }
}
