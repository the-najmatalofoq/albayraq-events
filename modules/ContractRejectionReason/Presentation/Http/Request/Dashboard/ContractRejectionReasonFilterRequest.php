<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class ContractRejectionReasonFilterRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
