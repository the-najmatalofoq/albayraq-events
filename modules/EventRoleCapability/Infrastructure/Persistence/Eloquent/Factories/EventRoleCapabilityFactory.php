<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Eloquent/Factories/EventRoleCapabilityFactory.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent\EventRoleCapabilityModel;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel;

final class EventRoleCapabilityFactory extends Factory
{
    protected $model = EventRoleCapabilityModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'event_role_assignment_id' => EventRoleAssignmentModel::factory(),
            'capability_key' => $this->faker->randomElement(['manage_shifts', 'see_wages', 'assign_staff', 'admin']),
            'is_granted' => $this->faker->boolean(80),
        ];
    }
}
