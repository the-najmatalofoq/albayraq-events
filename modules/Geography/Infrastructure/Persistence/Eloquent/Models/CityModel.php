<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// fix: add php docs
final class CityModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'cities';

    protected $fillable = [
        'id',
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
