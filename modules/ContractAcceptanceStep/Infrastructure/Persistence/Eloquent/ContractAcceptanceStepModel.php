<?php
// modules/ContractAcceptanceStep/Infrastructure/Persistence/Eloquent/ContractAcceptanceStepModel.php
declare(strict_types=1);

namespace Modules\ContractAcceptanceStep\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ContractAcceptanceStepModel extends Model
{
    use HasUuids;

    protected $table = 'contract_acceptance_steps';

    protected $keyType = 'string';

    public $incrementing = false;

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
        return $this->belongsTo(
            \Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel::class,
            'contract_id',
        );
    }
}
