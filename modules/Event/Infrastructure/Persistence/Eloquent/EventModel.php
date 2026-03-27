<?php
// modules/Event/Infrastructure/Persistence/Eloquent/EventModel.php
declare(strict_types=1);

namespace Modules\Event\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

final class EventModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'events';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'type',
        'start_date',
        'end_date',
        'latitude',
        'longitude',
        'price_amount',
        'price_currency',
        'status',
        'banner_id',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'start_date' => 'immutable_datetime',
        'end_date' => 'immutable_datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'price_amount' => 'integer',
        'status' => 'string',
    ];
}
