<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/UserSettingsModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Domain\Enum\LanguageEnum;

/**
 * UserSettings model — stores per-user preference data.
 *
 * @property string $id
 * @property string $user_id
 * @property LanguageEnum $preferred_locale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 */
final class UserSettingsModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'preferred_locale',
    ];

    protected function casts(): array
    {
        return [
            'preferred_locale' => LanguageEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
