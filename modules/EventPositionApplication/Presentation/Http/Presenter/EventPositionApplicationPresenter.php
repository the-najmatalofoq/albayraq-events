<?php
// modules/EventPositionApplication/Presentation/Http/Presenter/EventPositionApplicationPresenter.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Presenter;

use Modules\EventPositionApplication\Domain\EventPositionApplication;

final class EventPositionApplicationPresenter
{
    public function present(EventPositionApplication $application): array
    {
        return [
            'uuid'          => $application->uuid->value(),
            'user_id'       => $application->userId->value(),
            'position_id'    => $application->positionId->value(),
            'status'        => $application->status->value,
            'ranking_score'  => $application->rankingScore,
            'applied_at'     => $application->appliedAt->format(DATE_ATOM),
            'review'        => [
                'reviewed_at' => $application->reviewedAt?->format(DATE_ATOM),
                'reviewed_by' => $application->reviewedBy?->value(),
            ],
        ];
    }

    public function presentCollection(iterable $applications): array
    {
        $data = [];
        foreach ($applications as $application) {
            $data[] = $this->present($application);
        }
        return $data;
    }
}
