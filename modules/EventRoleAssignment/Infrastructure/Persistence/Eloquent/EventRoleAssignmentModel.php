<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/Eloquent/EventRoleAssignmentModel.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventRoleAssignmentModel extends Model
{
    use HasUuids;

    protected $table = 'event_role_assignments';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'user_id',
        'role_id',
    ];
}
