<?php

namespace Modules\Discount\Infrastructure\Persistence\Eloquent;

use Modules\Discount\Domain\Discount;
use Modules\Discount\Domain\Repository\DiscountRepositoryInterface;

class EloquentDiscountRepository implements DiscountRepositoryInterface
{
    public function findById(string $id): ?Discount
    {
        $model = DiscountModel::find($id);
        if (!$model) {
            return null;
        }

        return new Discount(
            $model->id,
            $model->discountable_id,
            $model->discountable_type,
            (string) $model->amount,
            $model->reason,
        );
    }

    public function save(Discount $discount): void
    {
        $model = DiscountModel::findOrNew($discount->id);
        $model->discountable_id = $discount->discountableId;
        $model->discountable_type = $discount->discountableType;
        $model->amount = $discount->amount;
        $model->reason = $discount->reason;
        $model->save();
    }
}
