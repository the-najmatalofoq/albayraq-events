<?php
// modules/DeductionType/Infrastructure/Persistence/Eloquent/DeductionTypeModel.php
declare(strict_types=1);

namespace Modules\DeductionType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;

final class DeductionTypeModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'deduction_types';

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
