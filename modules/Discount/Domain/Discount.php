<?php

namespace Modules\Discount\Domain;

class Discount
{
    public function __construct(
        public string $id,
        public string $discountableId,
        public string $discountableType,
        public string $amount,
        public string $reason,
    ) {}
}
