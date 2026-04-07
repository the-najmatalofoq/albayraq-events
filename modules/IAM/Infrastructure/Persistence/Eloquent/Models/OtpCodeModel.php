<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence\Eloquent\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

/**
 * @property string $id
 * @property string $user_id
 * @property string $code
 * @property OtpPurposeEnum $purpose
 * @property Carbon $expires_at
 * @property Carbon|null $verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 */
final class OtpCodeModel extends Model
{
    use HasUuids;

    protected $table = 'otp_codes';

    protected $fillable = [
        'user_id',
        'code',
        'purpose',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'purpose' => OtpPurposeEnum::class,
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
