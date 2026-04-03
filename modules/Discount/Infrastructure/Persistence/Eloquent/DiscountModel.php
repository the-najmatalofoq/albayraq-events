<?php

declare(strict_types=1);

namespace Modules\Discount\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Carbon\Carbon;

/**
 * Discount model for morphable resources
 * 
 * @property string $id
 * @property string $discountable_id
 * @property string $discountable_type
 * @property float $amount
 * @property string $reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $discountable
 */
final class DiscountModel extends Model
{
    use HasUuids;

    protected $table = 'discounts';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'discountable_id',
        'discountable_type',
        'amount',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }
}
