<?php
// modules/PenaltyType/Application/Query/GetPenaltyType/GetPenaltyTypeHandler.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Query\GetPenaltyType;

use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;
use Modules\PenaltyType\Domain\PenaltyType;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;

final readonly class GetPenaltyTypeHandler
{
    public function __construct(
        private PenaltyTypeRepositoryInterface $repository
    ) {}

    public function handle(GetPenaltyTypeQuery $query): ?PenaltyType
    {
        return $this->repository->findById(PenaltyTypeId::fromString($query->id));
    }
}
