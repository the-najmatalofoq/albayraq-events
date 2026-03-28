<?php
// modules/Event/Presentation/Http/Presenter/EventPresenter.php
declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Presenter;

use Modules\Event\Domain\Event;

// todo: make presneter for the location ,schedule, terms
final class EventPresenter
{
    public function present(Event $event): array
    {
        return [
            'uuid'          => $event->uuid->value(),
            'title'         => $event->title->toArray(),
            'description'   => $event->description?->toArray(),
            'location'      => [
                'latitude'  => $event->latitude,
                'longitude' => $event->longitude,
                'radius'    => $event->radius,
                'address'   => $event->address,
            ],
            'schedule'      => [
                'start_date' => $event->startDate->format('Y-m-d'),
                'end_date'   => $event->endDate->format('Y-m-d'),
                'start_time' => $event->startTime,
                'end_time'   => $event->endTime,
            ],
            'terms'         => [
                'employment_type' => $event->employmentType,
                'min_age'         => $event->minAge,
                'gender_rule'     => $event->genderRule,
            ],
            'status'        => $event->status->value,
            'is_published'  => $event->isPublished,
            'created_at'    => $event->createdAt->format(DATE_ATOM),
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
