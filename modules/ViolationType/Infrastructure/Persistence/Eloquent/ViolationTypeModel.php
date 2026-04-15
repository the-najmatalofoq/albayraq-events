<?php
// modules/ViolationType/Infrastructure/Persistence/Eloquent/ViolationTypeModel.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;
use Modules\ViolationType\Infrastructure\Persistence\Factories\ViolationTypeFactory;

final class ViolationTypeModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return ViolationTypeFactory::new();
    }
    protected $table = 'violation_types';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'name'      => TranslatableTextCast::class,
        'is_active' => 'boolean',
    ];
}
