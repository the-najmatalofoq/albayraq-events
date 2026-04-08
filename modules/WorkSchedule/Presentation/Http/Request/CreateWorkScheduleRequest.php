<?php
// modules/WorkSchedule/Presentation/Http/Request/CreateWorkScheduleRequest.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedulable_id'   => ['required', 'uuid'],
            'schedulable_type' => ['required', 'string', 'max:255'],
            'date'             => ['required', 'date', 'after_or_equal:today'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active'        => ['sometimes', 'boolean'],
        ];
    }
}
