<?php
// modules/EventBreakRequest/Presentation/Http/Requests/RejectBreakRequest.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RejectBreakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ];
    }
}
