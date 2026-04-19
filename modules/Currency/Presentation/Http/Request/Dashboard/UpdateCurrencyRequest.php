<?php
// modules/Currency/Presentation/Http/Request/UpdateCurrencyRequest.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('currency');

        return [
            'name' => ['sometimes', 'array'],
            'name.*' => ['string'],
            'code' => ['sometimes', 'string', 'size:3', 'unique:currencies,code,' . $id],
            'symbol' => ['sometimes', 'string', 'max:10'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
