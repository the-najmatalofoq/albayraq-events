<?php
// modules/Wage/Presentation/Http/Request/UpdateWageRequest.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateWageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'period' => ['sometimes', 'required', 'string', 'in:hourly,daily,monthly'],
            'currency_id' => ['nullable', 'uuid', 'exists:currencies,id'],
        ];
    }
}
