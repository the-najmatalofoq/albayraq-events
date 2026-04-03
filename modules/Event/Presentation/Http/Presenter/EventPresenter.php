<?php
// modules/Event/Presentation/Http/Presenter/EventPresenter.php
declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Presenter;

use Modules\Event\Domain\Enum\EventStatusEnum;
use Modules\Event\Domain\Event;

final class EventPresenter
{
    public function present(Event $event): array
    {
        return [
            'uuid' => $event->uuid->value,
            'title' => $event->name->toArray(),
            'description' => $event->description?->toArray(),
            'location' => EventLocationPresenter::present($event),
            'schedule' => EventSchedulePresenter::present($event),
            'terms' => EventTermsPresenter::present($event),
            'status' => $event->status->value,
            'is_published' => $event->status === EventStatusEnum::PUBLISHED,
            'created_at' => $event->createdAt->format(DATE_ATOM),
        ];
    }

    public function presentCollection(iterable $events): array
    {
        $data = [];
        foreach ($events as $event) {
            $data[] = $this->present($event);
        }
        return $data;
    }
}
