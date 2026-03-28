<?php
// modules/Shared/Infrastructure/Persistence/Eloquent/WageModel.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
