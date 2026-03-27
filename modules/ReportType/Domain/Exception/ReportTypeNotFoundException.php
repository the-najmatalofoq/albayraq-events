<?php
// modules/ReportType/Domain/Exception/ReportTypeNotFoundException.php
declare(strict_types=1);

namespace Modules\ReportType\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;

final class ReportTypeNotFoundException extends DomainException
{
    public static function withId(ReportTypeId $id): self
    {
        return new self("Report type with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
