<?php
// filePath: modules/EventPositionApplication/Infrastructure/Persistence/Eloquent/Factories/EventPositionApplicationFactory.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent\EventPositionApplicationModel;

final class EventPositionApplicationFactory extends Factory
{
    protected $model = EventPositionApplicationModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => \Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel::factory(),
            'position_id' => \Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'cancelled']),
            'ranking_score' => $this->faker->randomFloat(2, 0, 100),
            'applied_at' => now(),
            'reviewed_at' => $this->faker->optional()->dateTimeBetween('-1 month'),
            'reviewed_by' => null,
        ];
    }
}
