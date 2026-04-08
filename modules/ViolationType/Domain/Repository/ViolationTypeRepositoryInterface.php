<?php
// modules/ViolationType/Domain/Repository/ViolationTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ViolationType\Domain\Repository;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;

interface ViolationTypeRepositoryInterface
{
    public function nextIdentity(): ViolationTypeId;

    public function save(ViolationType $violationType): void;

    public function findById(ViolationTypeId $id): ?ViolationType;

    /**
     * @return ViolationType[]
     */
    public function listAll(): array;

    /**
     * @return array{items: ViolationType[], total: int}
     */
    public function paginate(
        PaginationCriteria $criteria,
        ?string $search = null
    ): array;

    public function delete(ViolationTypeId $id): void;
}
