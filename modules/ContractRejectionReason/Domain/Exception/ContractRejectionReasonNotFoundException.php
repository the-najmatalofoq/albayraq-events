<?php
// modules/ContractRejectionReason/Domain/Exception/ContractRejectionReasonNotFoundException.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;

final class ContractRejectionReasonNotFoundException extends DomainException
{
    public static function withId(ContractRejectionReasonId $id): self
    {
        return new self("Contract rejection reason with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
