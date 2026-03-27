<?php
// modules/EventTask/Infrastructure/Persistence/Eloquent/EventTaskModel.php
declare(strict_types=1);

namespace Modules\EventTask\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventTaskModel extends Model
{
    use HasUuids;

    protected $table = 'event_tasks';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'group_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'due_at',
        'created_by',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'due_at' => 'datetime',
    ];
}
