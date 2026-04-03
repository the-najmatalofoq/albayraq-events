<?php
// modules/FileAttachment/Infrastructure/Persistence/FileAttachmentReflector.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence;

use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;
use Modules\FileAttachment\Infrastructure\Persistence\Eloquent\AttachmentModel;

final class FileAttachmentReflector
{
    public function fromEntity(FileAttachment $attachment): array
    {
        return [
            'id' => $attachment->uuid->value,
            'attachable_id' => $attachment->attachableId,
            'attachable_type' => $attachment->attachableType,
            'file_path' => $attachment->filePath,
            'file_name' => $attachment->fileName,
            'file_type' => $attachment->fileType,
            'file_size' => $attachment->fileSize,
            'collection' => $attachment->collection,
        ];
    }

    public function toEntity(AttachmentModel $model): FileAttachment
    {
        return FileAttachment::reconstitute(
            uuid: new AttachmentId($model->id),
            attachableId: $model->attachable_id,
            attachableType: $model->attachable_type,
            filePath: $model->file_path,
            fileName: $model->file_name,
            fileType: $model->file_type,
            fileSize: (int) $model->file_size,
            collection: $model->collection,
        );
    }
}
