<?php

namespace Modules\ViolationType\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;
use Illuminate\Support\Str;

class ViolationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => ['en' => 'Late Arrival', 'ar' => 'تأخير في الحضور'],

            ],
            [
                'name' => ['en' => 'Absence without Notice', 'ar' => 'غياب بدون إبلاغ'],
            ],
            [
                'name' => ['en' => 'Safety Protocol Breach', 'ar' => 'مخالفة بروتوكول السلامة'],
            ],
            [
                'name' => ['en' => 'Wilful Damage', 'ar' => 'إتلاف متعمد'],
            ],
        ];

        foreach ($types as $type) {
            ViolationTypeModel::create([
                'id' => Str::uuid()->toString(),
                'name' => $type['name'],
                'slug' => Str::slug($type['name']['en']),
                'is_active' => true,
            ]);
        }
    }
}
