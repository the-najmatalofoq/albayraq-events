<?php

namespace Modules\Discount\Domain\Repository;

use Modules\Discount\Domain\Discount;

interface DiscountRepositoryInterface
{
    public function findById(string $id): ?Discount;

    public function save(Discount $discount): void;
}
