<?php
// modules/Currency/Infrastructure/Persistence/Eloquent/Models/CurrencyModel.php
declare(strict_types=1);

namespace Modules\Currency\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Shared\Infrastructure\Laravel\Casts\TranslatableTextCast;
use Modules\Shared\Domain\ValueObject\TranslatableText;

/**
 * @property string $id
 * @property TranslatableText $name
 * @property string $code
 * @property string $symbol
 * @property bool $is_active
 */
final class CurrencyModel extends Model
{
    use HasUuids;

    protected $table = 'currencies';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'code',
        'symbol',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'name' => TranslatableTextCast::class,
            'is_active' => 'boolean',
        ];
    }
}
