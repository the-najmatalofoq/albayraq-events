<?php
// modules/IAM/Infrastructure/Persistence/Eloquent/RoleModel.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class RoleModel extends Model
{
    use HasUuids;

    protected $table = 'roles';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'name',
        'is_global',
        'level',
    ];

    protected $casts = [
        'name' => 'array',
        'is_global' => 'boolean',
        'level' => 'integer',
    ];
}
