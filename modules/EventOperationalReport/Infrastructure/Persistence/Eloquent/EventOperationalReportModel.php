<?php
// modules/EventOperationalReport/Infrastructure/Persistence/Eloquent/EventOperationalReportModel.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\ReportType\Infrastructure\Persistence\Eloquent\ReportTypeModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

final class EventOperationalReportModel extends Model
{
    use HasUuids;

    protected $table = 'event_operational_reports';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'event_id',
        'report_type_id',
        'author_id',
        'title',
        'content',
        'date',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventModel::class, 'event_id');
    }

    public function reportType(): BelongsTo
    {
        return $this->belongsTo(ReportTypeModel::class, 'report_type_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'author_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'approved_by');
    }
}
