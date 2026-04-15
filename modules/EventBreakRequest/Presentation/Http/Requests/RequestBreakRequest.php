<?php
// modules/EventBreakRequest/Presentation/Http/Requests/RequestBreakRequest.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RequestBreakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participation_id' => ['required', 'uuid', 'exists:event_participations,id'],
            'date'             => ['required', 'date_format:Y-m-d'],
            'start_time'       => ['required', 'date_format:H:i:s'],
            'end_time'         => ['required', 'date_format:H:i:s', 'after:start_time'],
        ];
    }
}
