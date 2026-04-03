<?php
// modules/FileAttachment/Infrastructure/Persistence/Eloquent/AttachmentModel.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * File attachment model for morphable resources
 *
 * @property string $id
 * @property string $attachable_id
 * @property string $attachable_type
 * @property string $file_path
 * @property string $file_name
 * @property string $file_type
 * @property int|null $file_size
 * @property string|null $collection
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model $attachable
 */
final class AttachmentModel extends Model
{
    use HasUuids;

    protected $table = 'attachments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'attachable_id',
        'attachable_type',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'collection',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
