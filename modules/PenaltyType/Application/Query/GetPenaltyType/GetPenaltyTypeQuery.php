<?php
// modules/PenaltyType/Application/Query/GetPenaltyType/GetPenaltyTypeQuery.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Query\GetPenaltyType;

final readonly class GetPenaltyTypeQuery
{
    public function __construct(
        public string $id
    ) {}
}
