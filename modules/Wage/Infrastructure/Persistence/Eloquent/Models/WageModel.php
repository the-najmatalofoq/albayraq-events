<?php
// modules/Wage/Infrastructure/Persistence/Eloquent/Models/WageModel.php
declare(strict_types=1);

namespace Modules\Wage\Infrastructure\Persistence\Eloquent\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Wage model for morphable resources
 *
 * @property string $id
 * @property string $wageable_id
 * @property string $wageable_type
 * @property float $amount
 * @property string $currency
 * @property string $currency_id
 * @property string $period
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $wageable
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
        'currency_id',
        'period',
    ];

    public function currency_ref()
    {
        return $this->belongsTo(\Modules\Currency\Infrastructure\Persistence\Eloquent\Models\CurrencyModel::class, 'currency_id');
    }

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
