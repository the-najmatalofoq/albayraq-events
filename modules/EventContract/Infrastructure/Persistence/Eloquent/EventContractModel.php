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
