<?php

namespace Modules\User\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\ContactPhoneModel;

class ContactPhoneFactory extends Factory
{
    protected $model = ContactPhoneModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => null,
            'name' => $this->faker->name(),
            'phone' => '05' . $this->faker->numerify('########'),
            'relation' => $this->faker->randomElement(['father', 'mother', 'brother', 'sister', 'friend', 'other']),
        ];
    }
}
