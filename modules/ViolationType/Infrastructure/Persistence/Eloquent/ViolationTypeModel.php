<?php
// modules/ViolationType/Infrastructure/Persistence/Eloquent/ViolationTypeModel.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

/**
 * Violation type model
 *
 * @property string $id
 * @property array $name
 * @property float $default_deduction_amount
 * @property string $default_deduction_currency
 * @property string $severity
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class ViolationTypeModel extends Model
{
    use HasUuids;

    protected $table = 'violation_types';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'default_deduction_amount',
        'default_deduction_currency',
        'severity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'default_deduction_amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
