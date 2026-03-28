<?php
// modules/User/Infrastructure/Persistence/Eloquent/EmployeeProfileModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EmployeeProfileModel extends Model
{
    use HasUuids;

    protected $table = 'employee_profiles';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'nationality',
        'gender',
        'national_id',
        'medical_record',
        'height',
        'weight',
    ];

    protected $casts = [
        'full_name' => 'array',
        'birth_date' => 'date',
        'medical_record' => 'array',
        'height' => 'float',
        'weight' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
