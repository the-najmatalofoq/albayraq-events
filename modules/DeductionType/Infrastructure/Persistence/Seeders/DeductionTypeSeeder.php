<?php
// modules/DeductionType/Infrastructure/Persistence/Seeders/DeductionTypeSeeder.php
declare(strict_types=1);

namespace Modules\DeductionType\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\DeductionType\Infrastructure\Persistence\Eloquent\DeductionTypeModel;
use Illuminate\Support\Str;

class DeductionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => ['en' => 'Standard Deduction', 'ar' => 'خصم قياسي'],
                'slug' => 'standard-deduction',
            ],
            [
                'name' => ['en' => 'Special Deduction', 'ar' => 'خصم خاص'],
                'slug' => 'special-deduction',
            ],
        ];

        foreach ($types as $type) {
            DeductionTypeModel::updateOrCreate(
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
