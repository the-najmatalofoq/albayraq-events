<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * @property string $id
 * @property string $country_id
 * @property string|null $state_id
 * @property array|null $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read CountryModel $country
 * @property-read StateModel|null $state
 */
final class CityModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cities';

    protected $fillable = [
        'country_id',
        'state_id',
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryModel::class, 'country_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(StateModel::class, 'state_id');
    }
}
