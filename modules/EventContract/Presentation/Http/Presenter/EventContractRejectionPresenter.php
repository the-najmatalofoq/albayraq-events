<?php

declare(strict_types=1);

namespace Modules\EventContract\Presentation\Http\Presenter;

use Modules\EventContract\Domain\EventContract;

final class EventContractRejectionPresenter
{
    public static function present(EventContract $contract): ?array
    {
        if ($contract->rejectionReasonId === null && $contract->rejectionNotes === null) {
            return null;
        }

        return [
            'reason_id' => $contract->rejectionReasonId?->value,
            'notes' => $contract->rejectionNotes,
        ];
    }
}
