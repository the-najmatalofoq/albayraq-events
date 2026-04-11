<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Eloquent/EventRoleCapabilityModel.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel;
use Carbon\Carbon;

/**
 * Event role capability model
 *
 * @property string $id
 * @property string $event_role_assignment_id
 * @property string $capability_key
 * @property bool $is_granted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read EventRoleAssignmentModel $assignment
 */
final class EventRoleCapabilityModel extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $table = 'event_role_capabilities';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_role_assignment_id',
        'capability_key',
        'is_granted',
    ];

    protected $casts = [
        'is_granted' => 'boolean',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(EventRoleAssignmentModel::class, 'event_role_assignment_id');
    }

    protected static function newFactory()
    {
        return \Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent\Factories\EventRoleCapabilityFactory::new();
    }
}
