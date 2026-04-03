<?php

declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Presenter;

use Modules\Event\Domain\Event;

final class EventSchedulePresenter
{
    public static function present(Event $event): array
    {
        return [
            'start_date' => $event->startDate->format('Y-m-d'),
            'end_date' => $event->endDate->format('Y-m-d'),
            'start_time' => $event->dailyStartTime,
            'end_time' => $event->dailyEndTime,
        ];
    }
}
