<?php
// modules/ReportType/Presentation/Http/Request/ListReportTypesPaginationRequest.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

final class ListReportTypesPaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'      => ['sometimes', 'integer', 'min:1'],
            'per_page'  => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
