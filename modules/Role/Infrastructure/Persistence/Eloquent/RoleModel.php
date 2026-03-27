<?php
// modules/Role/Infrastructure/Persistence/Eloquent/RoleModel.php
declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class RoleModel extends Model
{
    use HasUuids, HasTranslations;

    protected $table = 'roles';

    protected $keyType = 'string';

    public $incrementing = false;

    public array $translatable = ['name'];

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
            'level' => 'integer',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(
            \Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel::class,
            'role_id',
        );
    }
}
