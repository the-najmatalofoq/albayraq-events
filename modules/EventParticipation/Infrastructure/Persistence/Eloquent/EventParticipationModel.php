<?php
// modules/EventParticipation/Infrastructure/Persistence/Eloquent/EventParticipationModel.php
declare(strict_types=1);

namespace Modules\EventParticipation\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel;
use Modules\EventStaffingGroup\Infrastructure\Persistence\Eloquent\EventStaffingGroupModel;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel;
use Modules\EventAttendance\Infrastructure\Persistence\Eloquent\EventAttendanceModel;
use Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent\ParticipationEvaluationModel;
use Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent\ParticipationViolationModel;
use Modules\EventParticipationBadge\Infrastructure\Persistence\Eloquent\EventParticipationBadgeModel;
use Modules\EventExperienceCertificate\Infrastructure\Persistence\Eloquent\EventExperienceCertificateModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Event participation model
 *
 * @property string $id
 * @property string $user_id
 * @property string $event_id
 * @property string $position_id
 * @property string|null $group_id
 * @property string $employee_number
 * @property string $status
 * @property Carbon $started_at
 * @property Carbon|null $ended_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read UserModel $user
 * @property-read EventModel $event
 * @property-read EventStaffingPositionModel $position
 * @property-read EventStaffingGroupModel|null $group
 * @property-read EventContractModel|null $contract
 * @property-read Collection|EventAttendanceModel[] $attendanceRecords
 * @property-read Collection|ParticipationEvaluationModel[] $evaluations
 * @property-read Collection|ParticipationViolationModel[] $violations
 * @property-read EventParticipationBadgeModel|null $badge
 * @property-read EventExperienceCertificateModel|null $certificate
 */
final class EventParticipationModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'event_participations';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'event_id',
        'position_id',
        'group_id',
        'employee_number',
        'status',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(EventStaffingPositionModel::class, 'position_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(EventStaffingGroupModel::class, 'group_id');
    }

    public function contract(): HasOne
    {
        return $this->hasOne(EventContractModel::class, 'event_participation_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(EventAttendanceModel::class, 'event_participation_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(ParticipationEvaluationModel::class, 'event_participation_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(ParticipationViolationModel::class, 'event_participation_id');
    }

    public function badge(): HasOne
    {
        return $this->hasOne(EventParticipationBadgeModel::class, 'event_participation_id');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(EventExperienceCertificateModel::class, 'event_participation_id');
    }
}
