<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ListCitiesByStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state_id' => ['required', 'uuid', 'exists:states,id'],
        ];
    }
}
