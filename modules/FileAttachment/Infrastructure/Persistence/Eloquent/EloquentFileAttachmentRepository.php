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
        FileAttachmentModel::updateOrCreate(
            ['id' => $attachment->uuid->value],
            [
                'original_name' => $attachment->originalName,
                'storage_path' => $attachment->storagePath,
                'mime_type' => $attachment->mimeType,
                'size' => $attachment->size,
                'uploader_id' => $attachment->uploaderId->value,
            ]
        );
    }

    public function findById(AttachmentId $id): ?FileAttachment
    {
        $model = FileAttachmentModel::find($id->value);
        return $model ? FileAttachmentReflector::fromModel($model) : null;
    }
}
