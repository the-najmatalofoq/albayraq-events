<?php
// modules/Role/Infrastructure/Persistence/Eloquent/RoleModel.php
declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Role\Domain\Enum\RoleLevelEnum;
use Carbon\Carbon;
use Modules\Role\Infrastructure\Persistence\Factories\RoleFactory;

/**
 * Role model
 *
 * @property string $id
 * @property string $slug
 * @property array $name
 * @property bool $is_global
 * @property RoleLevelEnum $level
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class RoleModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return RoleFactory::new();
    }

    protected $table = 'roles';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'slug',
        'name',
        'is_global',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_global' => 'boolean',
            'level' => RoleLevelEnum::class,
        ];
    }
}
