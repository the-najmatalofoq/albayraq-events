<?php
// modules/EventAnnouncement/Infrastructure/Persistence/Eloquent/EventAnnouncementModel.php
declare(strict_types=1);

namespace Modules\EventAnnouncement\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

final class EventAnnouncementModel extends Model
{
    use HasUuids, HasTranslations;

    protected $table = 'event_announcements';

    protected $keyType = 'string';

    public $incrementing = false;

    public array $translatable = ['title', 'body'];

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
        return $this->belongsTo(
            \Modules\Event\Infrastructure\Persistence\Eloquent\EventModel::class,
            'event_id',
        );
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\User\Infrastructure\Persistence\Eloquent\UserModel::class,
            'sender_id',
        );
    }
}
