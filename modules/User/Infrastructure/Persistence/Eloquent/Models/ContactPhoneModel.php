<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/ContactPhoneModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Modules\User\Infrastructure\Persistence\Factories\ContactPhoneFactory;

/**
 * Contact phone model - Emergency contact phone numbers
 *
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $phone
 * @property string $relation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 */
final class ContactPhoneModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return ContactPhoneFactory::new();
    }

    protected $table = 'contact_phones';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'relation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
