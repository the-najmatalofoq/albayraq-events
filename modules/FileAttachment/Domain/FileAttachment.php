<?php
// modules/FileAttachment/Domain/FileAttachment.php
declare(strict_types=1);

namespace Modules\FileAttachment\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;

final class FileAttachment extends AggregateRoot
{
    private function __construct(
        public readonly AttachmentId $uuid,
        public readonly string $attachableId,
        public readonly string $attachableType,
        public readonly string $filePath,
        public readonly string $fileName,
        public readonly string $fileType,
        public readonly int $fileSize,
        public readonly string $collection,
    ) {}

    public static function create(
        AttachmentId $uuid,
        string $attachableId,
        string $attachableType,
        string $filePath,
        string $fileName,
        string $fileType,
        int $fileSize,
        string $collection = 'default',
    ): self {
        return new self($uuid, $attachableId, $attachableType, $filePath, $fileName, $fileType, $fileSize, $collection);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
