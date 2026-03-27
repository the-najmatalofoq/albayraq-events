<?php
// modules/FileAttachment/Infrastructure/Persistence/Eloquent/FileAttachmentModel.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class FileAttachmentModel extends Model
{
    use HasUuids;

    protected $table = 'file_attachments';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'original_name',
        'storage_path',
        'mime_type',
        'size',
        'uploader_id',
    ];

    protected $casts = [
        'size' => 'integer',
    ];
}
