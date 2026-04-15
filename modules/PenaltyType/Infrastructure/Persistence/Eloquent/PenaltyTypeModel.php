<?php
// modules/PenaltyType/Infrastructure/Persistence/Eloquent/PenaltyTypeModel.php
declare(strict_types=1);

namespace Modules\PenaltyType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;

final class PenaltyTypeModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penalty_types';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'name'      => TranslatableTextCast::class,
        'is_active' => 'boolean',
    ];
}
