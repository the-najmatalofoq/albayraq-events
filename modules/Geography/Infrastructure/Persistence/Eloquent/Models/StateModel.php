<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// fix: use php docs
final class StateModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'states';

    protected $fillable = [
        'id',
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
