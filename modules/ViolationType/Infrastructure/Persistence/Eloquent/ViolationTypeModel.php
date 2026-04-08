<?php
// modules/ViolationType/Infrastructure/Persistence/Eloquent/ViolationTypeModel.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class ViolationTypeModel extends Model
{
    protected $table = 'violation_types';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'default_deduction_amount',
        'default_deduction_currency',
        'severity',
        'event_id',
        'is_active',
    ];

    protected $casts = [
        'name'      => 'array',
        'is_active' => 'boolean',
    ];
}
