<?php
// modules/EventOperationalReport/Infrastructure/Persistence/Eloquent/EventOperationalReportModel.php
declare(strict_types=1);

namespace Modules\EventOperationalReport\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class EventOperationalReportModel extends Model
{
    use HasUuids;

    protected $table = 'event_operational_reports';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'report_type_id',
        'content',
        'reported_by',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
