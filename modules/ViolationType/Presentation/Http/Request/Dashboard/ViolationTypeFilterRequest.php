<?php

declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request\Dashboard;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class ViolationTypeFilterRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'severity' => ['sometimes', 'string', 'in:low,medium,high'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
