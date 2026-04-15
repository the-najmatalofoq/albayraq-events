<?php
// modules/Currency/Presentation/Http/Request/CreateCurrencyRequest.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'json'],
            'name.*' => ['string'],
            'code' => ['required', 'string', 'size:3', 'unique:currencies,code'],
            'symbol' => ['required', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ];
    }
}
