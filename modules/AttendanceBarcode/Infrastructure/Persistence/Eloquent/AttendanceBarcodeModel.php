<?php
// modules/AttendanceBarcode/Infrastructure/Persistence/Eloquent/AttendanceBarcodeModel.php
declare(strict_types=1);

namespace Modules\AttendanceBarcode\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

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
