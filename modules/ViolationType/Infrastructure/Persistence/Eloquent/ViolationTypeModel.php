<?php
// modules/ViolationType/Infrastructure/Persistence/Eloquent/ViolationTypeModel.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class ViolationTypeModel extends Model
{
    use HasUuids;

    protected $table = 'violation_types';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'default_deduction_amount',
        'default_deduction_currency',
        'severity',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'default_deduction_amount' => 'float',
        'is_active' => 'boolean',
    ];
}
