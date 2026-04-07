<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Request\Dashboard;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class RoleFilterRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'level' => ['sometimes', 'string'],
            'is_global' => ['sometimes', 'boolean'],
        ]);
    }
}
