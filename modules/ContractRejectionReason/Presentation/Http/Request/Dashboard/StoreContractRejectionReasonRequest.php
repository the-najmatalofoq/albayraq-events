<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class StoreContractRejectionReasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'array'],
            'reason.ar' => ['required', 'string', 'max:255'],
            'reason.en' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
