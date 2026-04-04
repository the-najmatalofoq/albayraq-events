<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/UserModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

/**
 * User model - Primary authentication and authorization model
 *
 * @property string $id
 * @property array $name
 * @property string|null $email
 * @property string $phone
 * @property string $password
 * @property string|null $national_id
 * @property string|null $avatar
 * @property bool $is_active
 * @property Carbon|null $phone_verified_at
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|RoleModel[] $roles
 * @property-read EmployeeProfileModel|null $profile
 * @property-read BankDetailModel|null $bankDetails
 * @property-read Collection|ContactPhoneModel[] $contactPhones
 */
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

    public function profile(): HasOne
    {
        return $this->hasOne(EmployeeProfileModel::class, 'user_id');
    }

    public function bankDetails(): HasOne
    {
        return $this->hasOne(BankDetailModel::class, 'user_id');
    }

    public function contactPhones(): HasMany
    {
        return $this->hasMany(ContactPhoneModel::class, 'user_id');
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(UserJoinRequestModel::class, 'user_id');
    }

    public function latestJoinRequest(): HasOne
    {
        return $this->hasOne(UserJoinRequestModel::class, 'user_id')
                    ->latestOfMany();
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->id;
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
