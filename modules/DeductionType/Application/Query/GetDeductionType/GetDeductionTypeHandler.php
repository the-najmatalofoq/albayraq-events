<?php
// modules/DeductionType/Application/Query/GetDeductionType/GetDeductionTypeHandler.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Query\GetDeductionType;

use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;
use Modules\DeductionType\Domain\DeductionType;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;

final readonly class GetDeductionTypeHandler
{
    public function __construct(
        private DeductionTypeRepositoryInterface $repository
    ) {}

    public function handle(GetDeductionTypeQuery $query): ?DeductionType
    {
        return $this->repository->findById(DeductionTypeId::fromString($query->id));
    }
}
