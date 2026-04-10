<?php
// modules/EventJoinRequest/Domain/Exception/GeoFeasibilityException.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class GeoFeasibilityException extends DomainException
{
    public static function create(string $fromEvent, string $toEvent): self
    {
        return new self("Insufficient travel time between event '{$fromEvent}' and '{$toEvent}'.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::GEO_INFEASIBLE;
    }
}
