<?php

namespace Modules\Discount\Domain\Repository;

use Modules\Discount\Domain\Discount;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface DiscountRepositoryInterface
{
    public function findById(string $id): ?Discount;

    public function save(Discount $discount): void;
}
