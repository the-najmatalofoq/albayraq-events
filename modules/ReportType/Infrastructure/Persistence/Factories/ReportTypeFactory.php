<?php

namespace Modules\ReportType\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\ReportType\Infrastructure\Persistence\Eloquent\ReportTypeModel;

class ReportTypeFactory extends Factory
{
    protected $model = ReportTypeModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'slug' => $this->faker->unique()->slug(2),
            'name' => [
                'en' => $this->faker->words(2, true),
                'ar' => $this->faker->words(2, true),
            ],
            'is_active' => true,
        ];
    }
}
