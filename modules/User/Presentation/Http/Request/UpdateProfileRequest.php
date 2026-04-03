<?php
// modules/User/Presentation/Http/Request/UpdateProfileRequest.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'national_id' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:20', 'max:500'],
        ];
    }
}
