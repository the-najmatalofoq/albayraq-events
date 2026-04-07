<?php
declare(strict_types=1);

namespace Modules\Shared\Presentation\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

abstract class BaseFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:100'],
            'sort_by' => ['sometimes', 'string'],
            'sort_dir' => ['sometimes', 'in:asc,desc'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function toFilterCriteria(): FilterCriteria
    {
        return FilterCriteria::fromArray($this->validated());
    }

    public function getPerPage(): int
    {
        return (int) $this->validated('per_page', 15);
    }

    public function getPage(): int
    {
        return (int) $this->validated('page', 1);
    }
}
