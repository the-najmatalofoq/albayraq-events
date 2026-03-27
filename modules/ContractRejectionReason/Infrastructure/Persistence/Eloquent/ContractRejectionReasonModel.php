<?php
// modules/ContractRejectionReason/Infrastructure/Persistence/Eloquent/ContractRejectionReasonModel.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class ContractRejectionReasonModel extends Model
{
    use HasUuids;

    protected $table = 'contract_rejection_reasons';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'reason',
        'is_active',
    ];

    protected $casts = [
        'reason' => 'array',
        'is_active' => 'boolean',
    ];
}
