<?php
// modules/FileAttachment/Infrastructure/Persistence/Eloquent/AttachmentModel.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
