<?php

namespace Modules\Role\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Role\Domain\Enum\RoleLevelEnum;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;

class RoleFactory extends Factory
{
    protected $model = RoleModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'slug' => $this->faker->unique()->slug(2),
            'name' => [
                'en' => $this->faker->jobTitle(),
                'ar' => $this->faker->jobTitle(),
            ],
            'is_global' => $this->faker->boolean(),
            'level' => $this->faker->randomElement(RoleLevelEnum::cases()),
        ];
    }
}
