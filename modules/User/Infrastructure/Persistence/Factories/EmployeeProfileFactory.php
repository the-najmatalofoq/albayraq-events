<?php

namespace Modules\User\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\NationalityModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;

class EmployeeProfileFactory extends Factory
{
    protected $model = EmployeeProfileModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => null,
            'full_name' => [
                'en' => $this->faker->name(),
                'ar' => $this->faker->name(),
            ],
            'identity_number' => $this->faker->numerify('##########'),
            'nationality_id' => NationalityModel::query()->inRandomOrder()->first()?->id ?? null,
            'birth_date' => $this->faker->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'height' => $this->faker->numberBetween(150, 200),
            'weight' => $this->faker->numberBetween(50, 120),
        ];
    }
}
