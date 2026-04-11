<?php
// filePath: modules/EventPositionApplication/Infrastructure/Persistence/Seeders/EventPositionApplicationSeeder.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent\EventPositionApplicationModel;

final class EventPositionApplicationSeeder extends Seeder
{
    public function run(): void
    {
        EventPositionApplicationModel::factory()->count(30)->create();
    }
}
