<?php

declare(strict_types=1);

namespace Modules\Notification\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Carbon\Carbon;

/**
 * Device token model for push notifications
 * 
 * @property string $id
 * @property string $user_id
 * @property string $device_id
 * @property string $token
 * @property string $platform
 * @property string|null $device_name
 * @property bool $is_active
 * @property Carbon|null $last_used_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 */
final class DeviceTokenModel extends Model
{
    protected $table = 'device_tokens';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'user_id',
        'device_id',
        'token',
        'platform',
        'device_name',
        'is_active',
        'last_used_at',
    ];
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
