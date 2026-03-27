<?php
// modules/ViolationType/Presentation/Http/Presenter/ViolationTypePresenter.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Presenter;

use Modules\ViolationType\Domain\ViolationType;

final class ViolationTypePresenter
{
    public static function fromDomain(ViolationType $violationType): array
    {
        return [
            'id' => $violationType->uuid->value,
            'name' => $violationType->name->toArray(),
            'default_deduction' => $violationType->defaultDeduction 
                ? [
                    'amount' => $violationType->defaultDeduction->amount,
                    'currency' => $violationType->defaultDeduction->currency,
                ] 
                : null,
            'severity' => $violationType->severity->value,
            'is_active' => $violationType->isActive,
        ];
    }
}
