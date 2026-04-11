<?php
// modules/Wage/Domain/Repository/WageRepositoryInterface.php
declare(strict_types=1);

namespace Modules\Wage\Domain\Repository;

use Modules\Wage\Domain\Wage;
use Modules\Wage\Domain\ValueObject\WageId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface WageRepositoryInterface
{
    public function nextIdentity(): WageId;
    public function save(Wage $wage): void;
    public function findById(WageId $id): ?Wage;
    public function findByWageable(string $wageableId, string $wageableType): array;
    public function delete(WageId $id): void;
}
