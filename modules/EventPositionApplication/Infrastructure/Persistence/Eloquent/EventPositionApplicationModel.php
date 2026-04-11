<?php
// filePath: modules/EventPositionApplication/Infrastructure/Persistence/Eloquent/EventPositionApplicationModel.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Event position application model
 *
 * @property string $id
 * @property string $user_id
 * @property string $position_id
 * @property string $status
 * @property float $ranking_score
 * @property Carbon $applied_at
 * @property Carbon|null $reviewed_at
 * @property string|null $reviewed_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
final class EventPositionApplicationModel extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'event_position_applications';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'position_id',
        'status',
        'ranking_score',
        'applied_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'ranking_score' => 'float',
            'applied_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function newFactory()
    {
        return \Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent\Factories\EventPositionApplicationFactory::new();
    }
}
