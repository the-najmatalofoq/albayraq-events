<?php
// modules/FileAttachment/Infrastructure/Persistence/Eloquent/Repositories/EloquentFileAttachmentRepository.php
declare(strict_types=1);

namespace Modules\FileAttachment\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;
use Modules\FileAttachment\Domain\Repository\FileAttachmentRepositoryInterface;
use Modules\FileAttachment\Infrastructure\Persistence\FileAttachmentReflector;
use Modules\FileAttachment\Infrastructure\Persistence\Eloquent\AttachmentModel;

final class EloquentFileAttachmentRepository implements FileAttachmentRepositoryInterface
{
    public function __construct(
        private readonly FileAttachmentReflector $reflector,
    ) {
    }

    public function nextIdentity(): AttachmentId
    {
        return AttachmentId::generate();
    }

    public function save(FileAttachment $attachment): void
    {
        $data = $this->reflector->fromEntity($attachment);

        AttachmentModel::updateOrCreate(
            ['id' => $attachment->uuid->value],
            $data
        );
    }

    public function findById(AttachmentId $id): ?FileAttachment
    {
        $model = AttachmentModel::find($id->value);
        return $model ? $this->reflector->toEntity($model) : null;
    }
}
