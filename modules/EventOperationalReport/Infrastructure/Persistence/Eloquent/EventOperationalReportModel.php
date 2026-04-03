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
use Carbon\Carbon;

/**
 * Event operational report model
 *
 * @property string $id
 * @property string $event_id
 * @property string $report_type_id
 * @property string $author_id
 * @property array $title
 * @property array $content
 * @property Carbon $date
 * @property string $status
 * @property string|null $approved_by
 * @property Carbon|null $approved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventModel $event
 * @property-read ReportTypeModel $reportType
 * @property-read UserModel $author
 * @property-read UserModel|null $approver
 */
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

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'content' => 'array',
            'date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

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
