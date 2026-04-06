<?php

declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;

/**
 * @property string $id
 * @property string $country_id
 * @property array|null $name
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read CountryModel $country
 */
final class NationalityModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nationalities';

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'name' => TranslatableTextCast::class,
    ];
}
