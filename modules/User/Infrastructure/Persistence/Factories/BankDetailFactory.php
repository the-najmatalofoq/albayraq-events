<?php

namespace Modules\User\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\BankDetailModel;

class BankDetailFactory extends Factory
{
    protected $model = BankDetailModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => null,
            'account_owner' => $this->faker->name(),
            'bank_name' => $this->faker->company() . ' Bank',
            'iban' => 'SA' . $this->faker->numerify('######################'),
        ];
    }
}
