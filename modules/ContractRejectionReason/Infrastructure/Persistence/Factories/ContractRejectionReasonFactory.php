<?php

namespace Modules\ContractRejectionReason\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent\ContractRejectionReasonModel;

class ContractRejectionReasonFactory extends Factory
{
    protected $model = ContractRejectionReasonModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'reason' => [
                'en' => $this->faker->sentence(),
                'ar' => $this->faker->sentence(),
            ],
            'is_active' => true,
        ];
    }
}
