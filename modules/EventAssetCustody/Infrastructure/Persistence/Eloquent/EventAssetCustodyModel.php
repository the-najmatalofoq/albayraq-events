<?php
// modules/EventAssetCustody/Infrastructure/Persistence/Eloquent/EventAssetCustodyModel.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventAssetCustody\Domain\Enum\CustodyStatusEnum;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class EventAssetCustodyModel extends Model
{
    use HasUuids;

    protected $table = 'event_asset_custodies';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'event_participation_id',
        'item_name',
        'description',
        'handed_at',
        'returned_at',
        'status',
        'handed_by',
    ];

    protected function casts(): array
    {
        return [
            'item_name' => 'array',
            'description' => 'array',
            'handed_at' => 'datetime',
            'returned_at' => 'datetime',
            'status' => CustodyStatusEnum::class,
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'handed_by');
    }
}
