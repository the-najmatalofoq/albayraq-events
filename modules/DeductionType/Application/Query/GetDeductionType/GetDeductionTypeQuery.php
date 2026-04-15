<?php
// modules/DeductionType/Application/Query/GetDeductionType/GetDeductionTypeQuery.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Query\GetDeductionType;

final readonly class GetDeductionTypeQuery
{
    public function __construct(
        public string $id
    ) {}
}
