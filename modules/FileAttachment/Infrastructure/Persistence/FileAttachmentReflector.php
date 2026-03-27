<?php
// modules/FileAttachment/Infrastructure/Persistence/FileAttachmentReflector.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence;

use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\FileAttachment\Infrastructure\Persistence\Eloquent\FileAttachmentModel;

final class FileAttachmentReflector
{
    public static function fromModel(FileAttachmentModel $model): FileAttachment
    {
        $reflection = new \ReflectionClass(FileAttachment::class);
        $attachment = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => AttachmentId::fromString($model->id),
            'originalName' => $model->original_name,
            'storagePath' => $model->storage_path,
            'mimeType' => $model->mime_type,
            'size' => $model->size,
            'uploaderId' => UserId::fromString($model->uploader_id),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($attachment, $value);
        }

        return $attachment;
    }
}
