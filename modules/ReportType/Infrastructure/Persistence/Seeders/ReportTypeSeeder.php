<?php

declare(strict_types=1);

namespace Modules\ReportType\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\ReportType\Infrastructure\Persistence\Eloquent\ReportTypeModel;

final class ReportTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'slug' => 'attendance',
                'name' => ['en' => 'Attendance', 'ar' => 'الحضور والإنصراف'],
            ],
            [
                'slug' => 'violation',
                'name' => ['en' => 'Violation', 'ar' => 'المخالفات'],
            ],
            [
                'slug' => 'tasks',
                'name' => ['en' => 'Tasks', 'ar' => 'المهام'],
            ],
            [
                'slug' => 'general',
                'name' => ['en' => 'General', 'ar' => 'عام'],
            ],
            [
                'slug' => 'maintenance',
                'name' => ['en' => 'Maintenance', 'ar' => 'الصيانة'],
            ],
        ];

        foreach ($types as $typeData) {
            ReportTypeModel::updateOrCreate(
                ['slug' => $typeData['slug']],
                [
                    'name' => $typeData['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
