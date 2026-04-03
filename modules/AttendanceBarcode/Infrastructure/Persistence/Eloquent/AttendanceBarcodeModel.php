<?php

declare(strict_types=1);

namespace Modules\AttendanceBarcode\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;
use Carbon\Carbon;

/**
 * Attendance barcode model
 *
 * @property string $id
 * @property string $event_participation_id
 * @property string $code
 * @property Carbon $generated_at
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventParticipationModel $participation
 */
final class AttendanceBarcodeModel extends Model
{
    use HasUuids;

    protected $table = 'attendance_barcodes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'event_participation_id',
        'code',
        'generated_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(EventParticipationModel::class, 'event_participation_id');
    }
}
