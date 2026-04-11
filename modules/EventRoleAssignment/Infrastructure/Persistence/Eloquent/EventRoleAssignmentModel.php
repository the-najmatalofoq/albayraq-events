<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/Eloquent/EventRoleAssignmentModel.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\Factories\EventRoleAssignmentFactory;
use Carbon\Carbon;

/**
 * Event role assignment model
 *
 * @property string $id
 * @property string $event_id
 * @property string $user_id
 * @property string $role_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read UserModel $user
 * @property-read RoleModel $role
 */
final class EventRoleAssignmentModel extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'event_role_assignments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_id',
        'user_id',
        'role_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }

    protected static function newFactory(): EventRoleAssignmentFactory
    {
        return EventRoleAssignmentFactory::new();
    }
}
