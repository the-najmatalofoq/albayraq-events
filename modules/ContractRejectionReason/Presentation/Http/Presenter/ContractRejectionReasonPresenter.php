<?php
// modules/ContractRejectionReason/Presentation/Http/Presenter/ContractRejectionReasonPresenter.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Presenter;

use Modules\ContractRejectionReason\Domain\ContractRejectionReason;

final class ContractRejectionReasonPresenter
{
    public static function fromDomain(ContractRejectionReason $reason): array
    {
        return [
            'id' => $reason->uuid->value,
            'reason' => $reason->reason->toArray(),
            'is_active' => $reason->isActive,
        ];
    }
}
