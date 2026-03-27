<?php
// modules/EventAssetCustody/Infrastructure/Persistence/Eloquent/EventAssetCustodyModel.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventAssetCustodyModel extends Model
{
    use HasUuids;

    protected $table = 'event_asset_custodies';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_participation_id',
        'item_name',
        'description',
        'handed_at',
        'returned_at',
        'status',
        'handed_by',
    ];

    protected $casts = [
        'item_name' => 'array',
        'description' => 'array',
        'handed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];
}
