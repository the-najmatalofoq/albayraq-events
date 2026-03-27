<?php
// modules/IAM/Infrastructure/Persistence/Eloquent/UserModel.php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

final class UserModel extends Model implements JWTSubject
{
    use HasUuids, Notifiable;

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'is_active',
        'phone_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'phone_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            RoleModel::class,
            'role_user',
            'user_id',
            'role_id',
            'id',
            'id'
        );
    }
}
