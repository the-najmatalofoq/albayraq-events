<?php

namespace Modules\ContractRejectionReason\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent\ContractRejectionReasonModel;

class ContractRejectionReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            ['en' => 'Incomplete documents', 'ar' => 'وثائق غير مكتملة'],
            ['en' => 'Invalid ID number', 'ar' => 'رقم هوية غير صالح'],
            ['en' => 'Expired personal identification', 'ar' => 'هوية شخصية منتهية الصلاحية'],
            ['en' => 'Inconsistent birth date', 'ar' => 'تاريخ ميلاد غير متطابق'],
            ['en' => 'Medical report missing', 'ar' => 'تقرير طبي مفقود'],
        ];

        foreach ($reasons as $reason) {
            ContractRejectionReasonModel::factory()->create([
                'reason' => $reason,
                'is_active' => true,
            ]);
        }
    }
}
