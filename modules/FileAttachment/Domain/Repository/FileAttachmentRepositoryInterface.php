<?php
// modules/FileAttachment/Domain/Repository/FileAttachmentRepositoryInterface.php
declare(strict_types=1);

namespace Modules\FileAttachment\Domain\Repository;

use Modules\FileAttachment\Domain\FileAttachment;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;

interface FileAttachmentRepositoryInterface
{
    public function nextIdentity(): AttachmentId;

    public function save(FileAttachment $attachment): void;

    public function findById(AttachmentId $id): ?FileAttachment;
}
