<?php
// modules/EventAnnouncement/Presentation/Http/Presenter/EventAnnouncementPresenter.php
declare(strict_types=1);

namespace Modules\EventAnnouncement\Presentation\Http\Presenter;

use Modules\EventAnnouncement\Domain\EventAnnouncement;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\User\Domain\User;

final class EventAnnouncementPresenter
{
    public function present(EventAnnouncement $announcement, ?User $sender = null): array
    {
        return [
            'uuid'          => $announcement->uuid->value,
            'event_id'       => $announcement->eventId->value,
            'title'         => $announcement->title->toArray(),
            'content'       => $announcement->content->toArray(),
            'sender_id'     => $announcement->senderId->value,
            'target'        => [
                'type' => $announcement->targetType,
                'data' => $announcement->targetData,
            ],
            'created_at'    => $announcement->createdAt->format(DATE_ATOM),
            'sender'        => $sender ? UserPresenter::fromDomain($sender) : null,
        ];
    }

    public function presentCollection(iterable $announcements): array
    {
        $data = [];
        foreach ($announcements as $announcement) {
            $data[] = $this->present($announcement);
        }
        return $data;
    }
}
