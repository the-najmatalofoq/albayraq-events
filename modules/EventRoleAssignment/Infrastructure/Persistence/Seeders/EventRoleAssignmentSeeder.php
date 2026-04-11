<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/Seeders/EventRoleAssignmentSeeder.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel;

final class EventRoleAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        EventRoleAssignmentModel::factory()->count(10)->create();
    }
}
