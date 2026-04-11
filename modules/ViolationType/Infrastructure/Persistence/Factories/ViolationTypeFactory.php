<?php

namespace Modules\ViolationType\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;

class ViolationTypeFactory extends Factory
{
    protected $model = ViolationTypeModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => [
                'en' => $this->faker->words(3, true),
                'ar' => $this->faker->words(3, true),
            ],
            'default_deduction_amount' => $this->faker->randomFloat(2, 50, 500),
            'default_deduction_currency' => 'SAR',
            'severity' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'event_id' => null,
            'is_active' => true,
        ];
    }
}
