<?php
// modules/FileAttachment/Domain/Exception/AttachmentNotFoundException.php
declare(strict_types=1);

namespace Modules\FileAttachment\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\FileAttachment\Domain\ValueObject\AttachmentId;

final class AttachmentNotFoundException extends DomainException
{
    public static function withId(AttachmentId $id): self
    {
        return new self("Attachment with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
