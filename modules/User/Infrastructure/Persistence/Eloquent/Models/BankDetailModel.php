<?php
// modules/User/Infrastructure/Persistence/Eloquent/Models/BankDetailModel.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Modules\User\Infrastructure\Persistence\Factories\BankDetailFactory;

/**
 * User bank detail model
 *
 * @property string $id
 * @property string $user_id
 * @property string $account_owner
 * @property string $bank_name
 * @property string $iban
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read UserModel $user
 */
final class BankDetailModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return BankDetailFactory::new();
    }

    protected $table = 'bank_details';

    protected $fillable = [
        'user_id',
        'account_owner',
        'bank_name',
        'iban',
    ];

    protected function casts(): array
    {
        return [
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
