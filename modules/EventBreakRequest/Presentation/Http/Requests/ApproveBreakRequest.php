<?php
// modules/EventBreakRequest/Presentation/Http/Requests/ApproveBreakRequest.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ApproveBreakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cover_employee_id' => ['nullable', 'uuid', 'exists:users,id'],
        ];
    }
}
