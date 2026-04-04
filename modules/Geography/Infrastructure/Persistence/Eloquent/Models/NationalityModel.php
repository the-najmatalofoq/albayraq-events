<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// fix: use php docs.
final class NationalityModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nationalities';

    protected $fillable = [
        'id',
        'country_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(CountryModel::class, 'country_id');
    }
}
