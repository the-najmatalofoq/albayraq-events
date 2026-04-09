<?php
// modules/ReportType/Infrastructure/Persistence/Eloquent/ReportTypeModel.php
declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Modules\ReportType\Infrastructure\Persistence\Factories\ReportTypeFactory;

/**
 * Report type model
 *
 * @property string $id
 * @property string $slug
 * @property array $name
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class ReportTypeModel extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return ReportTypeFactory::new();
    }

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
