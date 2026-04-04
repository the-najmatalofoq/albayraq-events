<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string $id
 * @property string $country_id
 * @property array|null $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read CountryModel $country
 * @property-read Collection|CityModel[] $cities
 */
final class StateModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'states';

    protected $fillable = [
        'country_id',
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryModel::class, 'country_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(CityModel::class, 'state_id');
    }
}
