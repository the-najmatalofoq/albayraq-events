<?php
// modules/WorkSchedule/Presentation/Http/Request/WorkScheduleFilterRequest.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Request;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class WorkScheduleFilterRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'schedulable_type' => ['sometimes', 'string'],
            'schedulable_id'   => ['sometimes', 'uuid'],
            'is_active'       => ['sometimes', 'boolean'],
        ]);
    }
}
