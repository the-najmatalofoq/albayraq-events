<?php
// modules/ContractAcceptanceStep/Infrastructure/Persistence/Eloquent/ContractAcceptanceStepModel.php
declare(strict_types=1);

namespace Modules\ContractAcceptanceStep\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel;
use Carbon\Carbon;

/**
 * Contract acceptance step model
 *
 * @property string $id
 * @property string $contract_id
 * @property int $step
 * @property Carbon|null $completed_at
 * @property array $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventContractModel $contract
 */
final class ContractAcceptanceStepModel extends Model
{
    use HasUuids;

    protected $table = 'contract_acceptance_steps';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'contract_id',
        'step',
        'completed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(EventContractModel::class, 'contract_id');
    }
}
