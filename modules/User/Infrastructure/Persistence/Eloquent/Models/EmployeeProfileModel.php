<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/EmployeeProfileModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FileAttachment\Infrastructure\Persistence\Eloquent\AttachmentModel;

// fix: we must use the php Docs in the Eloquent Models for better ide. 
final class EmployeeProfileModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        'birth_date',
        'nationality',
        'gender',
        'height',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'height' => 'float',
            'weight' => 'float',
        ];
    }

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
