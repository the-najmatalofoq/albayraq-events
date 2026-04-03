<?php
// modules/EventContract/Infrastructure/Persistence/Eloquent/EventContractModel.php
declare(strict_types=1);

namespace Modules\EventContract\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent\ContractRejectionReasonModel;
use Modules\ContractAcceptanceStep\Infrastructure\Persistence\Eloquent\ContractAcceptanceStepModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Event contract model
 * 
 * @property string $id
 * @property string $event_participation_id
 * @property string $contract_type
 * @property float $wage_amount
 * @property array $terms
 * @property string $status
 * @property string|null $rejection_reason_id
 * @property string|null $rejection_notes
 * @property Carbon|null $accepted_at
 * @property Carbon|null $rejected_at
 * @property Carbon|null $sent_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventParticipationModel $participation
 * @property-read ContractRejectionReasonModel|null $rejectionReason
 * @property-read Collection|ContractAcceptanceStepModel[] $acceptanceSteps
 */
final class EventContractModel extends Model
{
    use HasUuids;

    protected $table = 'event_contracts';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_participation_id',
        'contract_type',
        'wage_amount',
        'terms',
        'status',
        'rejection_reason_id',
        'rejection_notes',
        'accepted_at',
        'rejected_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'terms' => 'array',
            'wage_amount' => 'decimal:2',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }

    public function rejectionReason(): BelongsTo
    {
        return $this->belongsTo(ContractRejectionReasonModel::class, 'rejection_reason_id');
    }

    public function acceptanceSteps(): HasMany
    {
        return $this->hasMany(ContractAcceptanceStepModel::class, 'contract_id');
    }
}
