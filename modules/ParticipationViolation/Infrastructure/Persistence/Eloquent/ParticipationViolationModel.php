<?php
// modules/ParticipationViolation/Infrastructure/Persistence/Eloquent/ParticipationViolationModel.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ParticipationViolation\Domain\Enum\ViolationStatusEnum;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class ParticipationViolationModel extends Model
{
    use HasUuids;

    protected $table = 'participation_violations';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_participation_id',
        'violation_type_id',
        'reported_by',
        'description',
        'date',
        'current_tier',
        'status',
        'deduction_amount',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'current_tier' => 'integer',
            'status' => ViolationStatusEnum::class,
            'deduction_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ViolationTypeModel::class, 'violation_type_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'reported_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'approved_by');
    }
}
