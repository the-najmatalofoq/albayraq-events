<?php
// modules/ViolationType/Application/Query/GetViolationType/GetViolationTypeQuery.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Query\GetViolationType;

final readonly class GetViolationTypeQuery
{
    public function __construct(
        public string $id
    ) {}
}
