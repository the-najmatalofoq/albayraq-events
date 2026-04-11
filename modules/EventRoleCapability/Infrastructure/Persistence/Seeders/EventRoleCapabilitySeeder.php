<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Seeders/EventRoleCapabilitySeeder.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent\EventRoleCapabilityModel;

final class EventRoleCapabilitySeeder extends Seeder
{
    public function run(): void
    {
        EventRoleCapabilityModel::factory()->count(20)->create();
    }
}
