<?php
// modules/ViolationType/Application/Query/GetViolationType/GetViolationTypeHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Query\GetViolationType;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;

final readonly class GetViolationTypeHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(GetViolationTypeQuery $query): ?ViolationType
    {
        $id = ViolationTypeId::fromString($query->id);
        return $this->repository->findById($id);
    }
}
