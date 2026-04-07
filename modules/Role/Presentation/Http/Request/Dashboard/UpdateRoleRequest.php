<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Role\Domain\Enum\RoleLevelEnum;

final class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'slug' => ['sometimes', 'string', 'max:50', Rule::unique('roles', 'slug')->ignore($id)],
            'name' => ['sometimes', 'array'],
            'name.ar' => ['required_with:name', 'string', 'max:100'],
            'name.en' => ['required_with:name', 'string', 'max:100'],
            'level' => ['sometimes', Rule::enum(RoleLevelEnum::class)],
            'is_global' => ['sometimes', 'boolean'],
        ];
    }
}
