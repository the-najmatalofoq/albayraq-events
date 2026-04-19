<?php
// modules/PenaltyType/Domain/Repository/PenaltyTypeRepositoryInterface.php
declare(strict_types=1);

namespace Modules\PenaltyType\Domain\Repository;

use Modules\PenaltyType\Domain\PenaltyType;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface PenaltyTypeRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): PenaltyTypeId;

    public function save(PenaltyType $penaltyType): void;

    public function findById(PenaltyTypeId $id): ?PenaltyType;

    /**
     * @return array<int, PenaltyType>
     */
    public function listAll(): array;

    public function delete(PenaltyTypeId $id): void;
}
