<?php
// modules/PenaltyType/Infrastructure/Persistence/Seeders/PenaltyTypeSeeder.php
declare(strict_types=1);

namespace Modules\PenaltyType\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\PenaltyType\Infrastructure\Persistence\Eloquent\PenaltyTypeModel;
use Illuminate\Support\Str;

class PenaltyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => ['en' => 'Warning', 'ar' => 'إنذار'],
                'slug' => 'warning',
            ],
            [
                'name' => ['en' => 'Suspension', 'ar' => 'إيقاف'],
                'slug' => 'suspension',
            ],
        ];

        foreach ($types as $type) {
            PenaltyTypeModel::updateOrCreate(
                ['slug' => $type['slug']],
                [
                    'id' => Str::uuid()->toString(),
                    'name' => $type['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
