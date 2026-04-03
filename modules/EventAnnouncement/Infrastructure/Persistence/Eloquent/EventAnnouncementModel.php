<?php
// modules/EventAnnouncement/Infrastructure/Persistence/Eloquent/EventAnnouncementModel.php
declare(strict_types=1);

namespace Modules\EventAnnouncement\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Carbon\Carbon;

/**
 * Event announcement model
 * 
 * @property string $id
 * @property string $event_id
 * @property string $sender_id
 * @property string $target_type
 * @property string $target_id
 * @property array $title
 * @property array $body
 * @property Carbon $sent_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read UserModel $sender
 */
final class EventAnnouncementModel extends Model
{
    use HasUuids;

    protected $table = 'event_announcements';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'sender_id',
        'target_type',
        'target_id',
        'title',
        'body',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'body' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'sender_id');
    }
}
