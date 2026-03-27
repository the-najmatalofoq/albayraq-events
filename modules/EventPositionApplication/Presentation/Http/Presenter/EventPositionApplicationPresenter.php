<?php
// modules/EventPositionApplication/Presentation/Http/Presenter/EventPositionApplicationPresenter.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Presenter;

use Modules\EventPositionApplication\Domain\EventPositionApplication;

final class EventPositionApplicationPresenter
{
    public static function fromDomain(EventPositionApplication $application): array
    {
        return [
            'id' => $application->uuid->value,
            'user_id' => $application->userId->value,
            'position_id' => $application->positionId->value,
            'status' => $application->status->value,
            'status_label' => $application->status->label(),
            'ranking_score' => $application->rankingScore,
            'applied_at' => $application->appliedAt->format('Y-m-d H:i:s'),
            'reviewed_at' => $application->reviewedAt?->format('Y-m-d H:i:s'),
            'reviewed_by' => $application->reviewedBy?->value,
        ];
    }
}
