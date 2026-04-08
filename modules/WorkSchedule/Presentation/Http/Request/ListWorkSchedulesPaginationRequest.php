<?php
// modules/WorkSchedule/Presentation/Http/Request/ListWorkSchedulesPaginationRequest.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ListWorkSchedulesPaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'             => ['sometimes', 'integer', 'min:1'],
            'per_page'         => ['sometimes', 'integer', 'min:1', 'max:100'],
            'schedulable_type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'schedulable_id'   => ['sometimes', 'nullable', 'uuid'],
        ];
    }
}
