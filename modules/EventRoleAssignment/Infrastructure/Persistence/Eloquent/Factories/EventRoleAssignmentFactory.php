<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/Eloquent/Factories/EventRoleAssignmentFactory.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel;
use Modules\Event\Infrastructure\Persistence\Eloquent\EventModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;

final class EventRoleAssignmentFactory extends Factory
{
    protected $model = EventRoleAssignmentModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'event_id' => EventModel::factory(),
            'user_id' => UserModel::factory(),
            'role_id' => RoleModel::factory(),
        ];
    }
}
