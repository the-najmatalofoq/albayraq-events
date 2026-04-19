<?php
// modules/Wage/Presentation/Http/Request/CreateWageRequest.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateWageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wageable_id' => ['required', 'uuid'],
            'wageable_type' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'period' => ['required', 'string', 'in:hourly,daily,monthly,yearly'],
            'currency_id' => ['nullable', 'uuid', 'exists:currencies,id'],
        ];
    }
}
