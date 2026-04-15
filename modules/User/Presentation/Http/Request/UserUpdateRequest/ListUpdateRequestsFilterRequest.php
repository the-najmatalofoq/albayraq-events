<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request\UserUpdateRequest;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class ListUpdateRequestsFilterRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected'],
            'target_type' => ['sometimes', 'string'],
            'user_id' => ['sometimes', 'uuid'],
        ]);
    }
}
