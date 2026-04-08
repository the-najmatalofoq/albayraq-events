<?php
// modules/ReportType/Presentation/Http/Request/CreateReportTypeRequest.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class CreateReportTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'json'],
            'slug' => ['required', 'string', 'max:50', 'unique:report_types,slug'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
