<?php
// modules/User/Infrastructure/Persistence/Eloquent/EmployeeProfileModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use \Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;
use \Modules\FileAttachment\Infrastructure\Persistence\Eloquent\AttachmentModel;

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
        'height',
        'weight',
    ];

    protected $casts = [
        'full_name' => TranslatableTextCast::class,
        'birth_date' => 'date',
        'height' => 'float',
        'weight' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(AttachmentModel::class, 'attachable');
    }

    public function cv()
    {
        return $this->attachments()->where('collection', 'cv');
    }

    public function identityPersonal()
    {
        return $this->attachments()->where('collection', 'identity_personal');
    }

    public function medicalRecord()
    {
        return $this->attachments()->where('collection', 'medical_record');
    }
}
