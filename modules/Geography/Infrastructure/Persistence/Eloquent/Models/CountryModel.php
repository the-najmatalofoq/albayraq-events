<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string $id
 * @property string $code
 * @property array|null $name
 * @property string|null $phone_code
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read NationalityModel|null $nationality
 * @property-read Collection|StateModel[] $states
 * @property-read Collection|CityModel[] $cities
 */
final class CountryModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'countries';

    protected $fillable = [
        'code',
        'name',
        'phone_code',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function nationality(): HasOne
    {
        return $this->hasOne(NationalityModel::class, 'country_id');
    }

    public function states(): HasMany
    {
        return $this->hasMany(StateModel::class, 'country_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(CityModel::class, 'country_id');
    }
}
