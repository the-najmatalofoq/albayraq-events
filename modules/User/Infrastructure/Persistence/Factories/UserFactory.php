<?php

namespace Modules\User\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;

class UserFactory extends Factory
{
    protected $model = UserModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => [
                'en' => $this->faker->name(),
                'ar' => $this->faker->name(),
            ],
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '05' . $this->faker->numerify('########'),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];
    }
}
