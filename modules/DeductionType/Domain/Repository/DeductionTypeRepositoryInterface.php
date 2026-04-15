<?php
// modules/DeductionType/Domain/Repository/DeductionTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\DeductionType\Domain\Repository;

use Modules\DeductionType\Domain\DeductionType;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface DeductionTypeRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): DeductionTypeId;

    public function save(DeductionType $deductionType): void;

    public function findById(DeductionTypeId $id): ?DeductionType;

    /**
     * @return array<int, DeductionType>
     */
    public function listAll(): array;

    public function delete(DeductionTypeId $id): void;
}

