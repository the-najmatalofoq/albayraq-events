<?php
// modules/ReportType/Infrastructure/Persistence/Eloquent/ReportTypeModel.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class ReportTypeModel extends Model
{
    use HasUuids;

    protected $table = 'report_types';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'slug',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
