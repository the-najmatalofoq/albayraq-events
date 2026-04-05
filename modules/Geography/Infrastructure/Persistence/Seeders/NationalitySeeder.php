<?php
// modules/Geography/Infrastructure/Persistence/Seeders/NationalitySeeder.php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\NationalityModel;

final class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => ['ar' => 'السعودية', 'en' => 'Saudi Arabia']],
            ['name' => ['ar' => 'الإمارات', 'en' => 'United Arab Emirates']],
            ['name' => ['ar' => 'الكويت', 'en' => 'Kuwait']],
            ['name' => ['ar' => 'قطر', 'en' => 'Qatar']],
            ['name' => ['ar' => 'البحرين', 'en' => 'Bahrain']],
            ['name' => ['ar' => 'عمان', 'en' => 'Oman']],
            ['name' => ['ar' => 'الأردن', 'en' => 'Jordan']],
            ['name' => ['ar' => 'مصر', 'en' => 'Egypt']],
            ['name' => ['ar' => 'سوريا', 'en' => 'Syria']],
            ['name' => ['ar' => 'لبنان', 'en' => 'Lebanon']],
        ];

        foreach ($data as $item) {
            NationalityModel::updateOrCreate(
                ['name' => $item['name']],
            );
        }
    }
}
