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
            'reason' => ['required', 'json'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
