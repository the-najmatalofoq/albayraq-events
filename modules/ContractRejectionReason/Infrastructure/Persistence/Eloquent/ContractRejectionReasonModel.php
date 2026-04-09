<?php
// modules/ContractRejectionReason/Infrastructure/Persistence/Eloquent/ContractRejectionReasonModel.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Factories\ContractRejectionReasonFactory;

/**
 * Contract rejection reason model
 *
 * @property string $id
 * @property array $reason
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class ContractRejectionReasonModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return ContractRejectionReasonFactory::new();
    }

    protected $table = 'contract_rejection_reasons';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'reason',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'reason' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
