<?php
// modules/FileAttachment/Infrastructure/Persistence/AttachmentReflector.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence;

use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;
use Modules\FileAttachment\Infrastructure\Persistence\Eloquent\AttachmentModel;

final class AttachmentReflector
{
    public static function fromModel(AttachmentModel $model): FileAttachment
    {
        $reflection = new \ReflectionClass(FileAttachment::class);
        $attachment = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => AttachmentId::fromString($model->id),
            'attachableId'      => $model->attachable_id,
            'attachableType'    => $model->attachable_type,
            'filePath'          => $model->file_path,
            'fileName'          => $model->file_name,
            'fileType'          => $model->file_type,
            'fileSize'          => (int) $model->file_size,
            'collection'        => $model->collection,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($attachment, $value);
        }

        return $attachment;
    }
}
