<?php
// modules/WorkSchedule/Presentation/Http/Request/UpdateWorkScheduleRequest.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date'            => ['sometimes', 'date', 'after_or_equal:today'],
            'start_time'      => ['sometimes', 'date_format:H:i'],
            'end_time'        => ['sometimes', 'date_format:H:i', 'after:start_time'],
            'is_active'       => ['sometimes', 'boolean'],
        ];
    }
}
