<?php

namespace Modules\User\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\MedicalRecordModel;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecordModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => null,
            'blood_type' => $this->faker->randomElement(BloodTypeEnum::cases())->value,
            'chronic_diseases' => $this->faker->optional()->sentence(),
            'allergies' => $this->faker->optional()->sentence(),
            'medications' => $this->faker->optional()->sentence(),
        ];
    }
}
