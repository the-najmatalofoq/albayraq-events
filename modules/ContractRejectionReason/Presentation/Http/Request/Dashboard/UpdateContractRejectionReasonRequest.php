<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateContractRejectionReasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['sometimes', 'array'],
            'reason.ar' => ['required_with:reason', 'string', 'max:255'],
            'reason.en' => ['required_with:reason', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
