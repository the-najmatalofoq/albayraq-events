<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/UserModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

// fix: add php docs.
final class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'national_id',
        'avatar',
        'is_active',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_active' => 'boolean',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            RoleModel::class,
            'role_user',
            'user_id',
            'role_id'
        );
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
