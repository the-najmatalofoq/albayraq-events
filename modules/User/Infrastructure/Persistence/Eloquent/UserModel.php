<?php
// modules/User/Infrastructure/Persistence/Eloquent/UserModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;

final class UserModel extends Model implements JWTSubject
{
    use HasUuids, Notifiable, SoftDeletes;

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
        'national_id',
        'phone_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'phone_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
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

    public function profile(): HasOne
    {
        return $this->hasOne(EmployeeProfileModel::class, 'user_id');
    }

    public function contactPhones(): HasMany
    {
        return $this->hasMany(ContactPhoneModel::class, 'user_id');
    }

    public function bankDetails(): HasOne
    {
        return $this->hasOne(BankDetailModel::class, 'user_id');
    }
}
