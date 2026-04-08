<?php
// modules/ReportType/Presentation/Http/Request/UpdateReportTypeRequest.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateReportTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'json'],
            'slug' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
