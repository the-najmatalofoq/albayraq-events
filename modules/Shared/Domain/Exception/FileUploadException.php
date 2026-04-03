<?php
// modules/Shared/Domain/Exception/FileUploadException.php
declare(strict_types=1);

namespace Modules\Shared\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class FileUploadException extends DomainException
{
    public static function failed(string $reason): self
    {
        return new self("File upload failed: {$reason}");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::FILE_UPLOAD_FAILED;
    }
}
