<?php

declare(strict_types=1);

namespace Modules\Role\Presentation\Http\Request\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Role\Domain\Enum\RoleLevelEnum;
use Modules\Role\Domain\Enum\RoleSlugEnum;

final class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:50', 'unique:roles,slug'],
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:100'],
            'name.en' => ['required', 'string', 'max:100'],
            'level' => ['required', Rule::enum(RoleLevelEnum::class)],
            'is_global' => ['required', 'boolean'],
        ];
    }
}
