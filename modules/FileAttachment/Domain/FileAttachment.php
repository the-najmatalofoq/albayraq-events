<?php
// modules/FileAttachment/Domain/FileAttachment.php
declare(strict_types=1);

namespace Modules\FileAttachment\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;

final class FileAttachment extends AggregateRoot
{
    public function __construct(
        public readonly AttachmentId $uuid,
        public readonly string $originalName,
        public readonly string $storagePath,
        public readonly string $mimeType,
        public readonly int $size,
        public readonly UserId $uploaderId
    ) {}

    public static function create(
        AttachmentId $uuid,
        string $originalName,
        string $storagePath,
        string $mimeType,
        int $size,
        UserId $uploaderId
    ): self {
        return new self($uuid, $originalName, $storagePath, $mimeType, $size, $uploaderId);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
