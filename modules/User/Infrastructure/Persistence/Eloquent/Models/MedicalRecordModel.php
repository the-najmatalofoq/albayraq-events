<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/MedicalRecordModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Carbon\Carbon;
use Modules\User\Infrastructure\Persistence\Factories\MedicalRecordFactory;

/**
 * Medical Record model
 *
 * @property string $id
 * @property string $user_id
 * @property string $blood_type
 * @property string|null $chronic_diseases
 * @property string|null $allergies
 * @property string|null $medications
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 */
final class MedicalRecordModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return MedicalRecordFactory::new();
    }

    protected $table = 'medical_records';

    protected $fillable = [
        'user_id',
        'blood_type',
        'chronic_diseases',
        'allergies',
        'medications',
    ];

    protected function casts(): array
    {
        return [
            'blood_type' => BloodTypeEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
