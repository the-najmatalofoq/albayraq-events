<?php
// modules/ViolationType/Presentation/Http/Request/ListViolationTypesPaginationRequest.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Request;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class ListViolationTypesPaginationRequest extends BaseFilterRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
