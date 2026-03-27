<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Eloquent/EventRoleCapabilityModel.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventRoleCapabilityModel extends Model
{
    use HasUuids;

    protected $table = 'event_role_capabilities';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'assignment_id',
        'capability_key',
        'is_granted',
    ];

    protected $casts = [
        'is_granted' => 'boolean',
    ];
}
