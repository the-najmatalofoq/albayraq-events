<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Domain\Enum\JoinRequestStatusEnum;
use Carbon\Carbon;

/**
 * @property string               $id
 * @property string               $user_id
 * @property JoinRequestStatusEnum $status
 * @property string|null          $reviewed_by
 * @property Carbon|null          $reviewed_at
 * @property string|null          $notes
 * @property Carbon               $created_at
 * @property Carbon               $updated_at
 */
final class UserJoinRequestModel extends Model
{
    use HasUuids;

    protected $table = 'user_join_requests';

    protected $fillable = [
        'user_id',
        'status',
        'reviewed_by',
        'reviewed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'      => JoinRequestStatusEnum::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
