<?php
// modules/FileAttachment/Infrastructure/Persistence/Eloquent/AttachmentModel.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $attachable_id
 * @property string $attachable_type
 * @property string $file_path
 * @property string $file_name
 * @property string $file_type
 * @property int|null $file_size
 * @property string|null $collection
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Model|\Illuminate\Database\Eloquent\Model $attachable
 * 
 * @method static Builder|AttachmentModel newModelQuery()
 * @method static Builder|AttachmentModel newQuery()
 * @method static Builder|AttachmentModel query()
 * @method static Builder|AttachmentModel whereId($value)
 * @method static Builder|AttachmentModel whereAttachableId($value)
 * @method static Builder|AttachmentModel whereAttachableType($value)
 * @method static Builder|AttachmentModel whereFilePath($value)
 * @method static Builder|AttachmentModel whereFileName($value)
 * @method static Builder|AttachmentModel whereFileType($value)
 * @method static Builder|AttachmentModel whereFileSize($value)
 * @method static Builder|AttachmentModel whereCollection($value)
 * @method static Builder|AttachmentModel whereCreatedAt($value)
 * @method static Builder|AttachmentModel whereUpdatedAt($value)
 * 
 * @mixin Model
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