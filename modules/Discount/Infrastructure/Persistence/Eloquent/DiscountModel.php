<?php
// modules/Discount/Infrastructure/Persistence/Eloquent/DiscountModel.php
declare(strict_types=1);

namespace Modules\Discount\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
