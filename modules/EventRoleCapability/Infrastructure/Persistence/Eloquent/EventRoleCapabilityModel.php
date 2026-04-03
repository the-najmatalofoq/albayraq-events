<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Eloquent/EventRoleCapabilityModel.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Carbon\Carbon;

/**
 * Event role capability model
 *
 * @property string $id
 * @property string $event_id
 * @property string $user_id
 * @property string $capability
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read UserModel $user
 */
final class EventRoleCapabilityModel extends Model
{
    use HasUuids;

    protected $table = 'event_role_capabilities';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'user_id',
        'capability',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
