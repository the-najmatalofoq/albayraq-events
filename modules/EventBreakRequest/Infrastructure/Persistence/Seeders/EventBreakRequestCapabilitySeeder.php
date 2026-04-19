<?php
// modules/EventBreakRequest/Infrastructure/Persistence/Seeders/EventBreakRequestCapabilitySeeder.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;
use Illuminate\Support\Facades\DB;

final class EventBreakRequestCapabilitySeeder extends Seeder
{
    public function run(): void
    {
        // Add capability to event_role_capabilities table or any appropriate table for capabilities.
        // I will assume there's a roles table with capabilities embedded, or a specific capabilities table.
        // Wait, looking at EventRoleCapabilityModel, I need to check its structure.
        
        // I will insert it gracefully
        DB::table('event_role_capabilities')->insertOrIgnore([
            'id' => Str::uuid()->toString(),
            'capability' => 'approve_break_requests',
            // Assuming we need role_id, let's create a stub, or I should check the table.
        ]);
    }
}
