<?php
// modules/FileAttachment/Infrastructure/Persistence/Eloquent/EloquentFileAttachmentRepository.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence\Eloquent;

use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;
use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Infrastructure\Persistence\FileAttachmentReflector;

final class EloquentFileAttachmentRepository implements FileAttachmentRepositoryInterface
{
    public function nextIdentity(): AttachmentId
    {
        return AttachmentId::generate();
    }

    public function save(FileAttachment $attachment): void
    {
        AttachmentModel::updateOrCreate(
            ['id' => $attachment->uuid->value],
              [  
                'path' => $attachment->filePath,
                'file_name' => $attachment->fileName,
                'file_type' => $attachment->fileType,
                'attachable_id' => $attachment->attachableId,
                'attachable_type' => $attachment->attachableType,
                'file_size' => $attachment->fileSize,
                'collection' => $attachment->collection,]
            
        );
    }

    public function findById(AttachmentId $id): ?FileAttachment
    {
        $model = AttachmentModel::find($id->value);
        return $model ? FileAttachmentReflector::fromModel($model) : null;
    }
}
