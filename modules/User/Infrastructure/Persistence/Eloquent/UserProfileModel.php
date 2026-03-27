<?php
// modules/User/Infrastructure/Persistence/Eloquent/UserProfileModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class UserProfileModel extends Model
{
    use HasUuids;

    protected $table = 'user_profiles';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'employee_number',
        'job_title',
        'department',
        'hiring_date',
        'is_active',
    ];

    protected $casts = [
        'job_title' => 'array',
        'department' => 'array',
        'hiring_date' => 'immutable_date',
        'is_active' => 'boolean',
    ];
}
