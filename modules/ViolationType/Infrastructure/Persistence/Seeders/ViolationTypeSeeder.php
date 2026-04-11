<?php

namespace Modules\ViolationType\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\ViolationType\Infrastructure\Persistence\Eloquent\ViolationTypeModel;
use Database\Factories\ViolationTypeFactory;

class ViolationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => ['en' => 'Late Arrival', 'ar' => 'تأخير في الحضور'],
                'severity' => 'low',
                'amount' => 50,
            ],
            [
                'name' => ['en' => 'Absence without Notice', 'ar' => 'غياب بدون إبلاغ'],
                'severity' => 'medium',
                'amount' => 150,
            ],
            [
                'name' => ['en' => 'Safety Protocol Breach', 'ar' => 'مخالفة بروتوكول السلامة'],
                'severity' => 'high',
                'amount' => 300,
            ],
            [
                'name' => ['en' => 'Wilful Damage', 'ar' => 'إتلاف متعمد'],
                'severity' => 'critical',
                'amount' => 500,
            ],
        ];

        foreach ($types as $type) {
            ViolationTypeModel::factory()->create([
                'name' => $type['name'],
                'severity' => $type['severity'],
                'default_deduction_amount' => $type['amount'],
            ]);
        }
    }
}
