<?php
// modules/Role/Infrastructure/Persistence/Eloquent/RoleModel.php
declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Role\Domain\Enum\RoleLevelEnum;

final class RoleModel extends Model
{
    use HasUuids;

    protected $table = 'roles';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'slug',
        'name',
        'is_global',
        'level',
    ];

    protected $casts = [
        'name' => 'array',
        'is_global' => 'boolean',
        'level' => RoleLevelEnum::class,
    ];
}
