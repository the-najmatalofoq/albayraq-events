<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateBankDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_owner' => ['sometimes', 'string', 'max:255'],
            'bank_name' => ['sometimes', 'string', 'max:255'],
            'iban' => ['sometimes', 'string', 'max:34'],
        ];
    }
}
