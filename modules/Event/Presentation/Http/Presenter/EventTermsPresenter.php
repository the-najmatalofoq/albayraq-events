<?php

declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Presenter;

use Modules\Event\Domain\Event;

final class EventTermsPresenter
{
    public static function present(Event $event): array
    {
        return [
            'employment_terms' => $event->employmentTerms?->toArray(),
        ];
    }
}
