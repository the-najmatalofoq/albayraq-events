<?php
// modules/Wage/Infrastructure/Persistence/Eloquent/Models/WageModel.php
declare(strict_types=1);

namespace Modules\Wage\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $wageable_id
 * @property string $wageable_type
 * @property float $amount
 * @property string $currency
 * @property string $period
 */
final class WageModel extends Model
{
    use HasUuids;

    protected $table = 'wages';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'wageable_id',
        'wageable_type',
        'amount',
        'currency',
        'period',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function wageable(): MorphTo
    {
        return $this->morphTo();
    }
}
