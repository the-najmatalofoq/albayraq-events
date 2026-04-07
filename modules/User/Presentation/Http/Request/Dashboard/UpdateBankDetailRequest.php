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
            'account_owner' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', 'max:34'],
        ];
    }
}
