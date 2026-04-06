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
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\{
    CityModel,
    CountryModel,
    NationalityModel
};
use Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;

/**
 * Employee profile model - Extended user profile with personal details
 *
 * @property string $id
 * @property string $user_id
 * @property Carbon|null $birth_date
 * @property string|null $city_id
 * @property string|null $gender
 * @property float|null $height
 * @property float|null $weight
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read UserModel $user
 * @property-read Collection|AttachmentModel[] $attachments
 * @property-read CityModel|null $city
 * @property-read Collection|NationalityModel[] $nationalities
 * @property-read NationalityModel|null $primary_nationality
 * @property-read CountryModel|null $residence_country
 */
final class EmployeeProfileModel extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        'full_name',
        'identity_number',
        'nationality_id',
        'birth_date',
        'gender',
        'height',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'full_name' => TranslatableTextCast::class,
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

    public function nationality()
    {
        return $this->belongsTo(
            NationalityModel::class,
            'nationality_id',
            'id'
        );
    }
}
