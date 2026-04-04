<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

final class GeographySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'SA', 'phone' => '+966', 'en' => 'Saudi Arabia', 'ar' => 'المملكة العربية السعودية', 'nat_en' => 'Saudi', 'nat_ar' => 'سعودي'],
            ['code' => 'AE', 'phone' => '+971', 'en' => 'United Arab Emirates', 'ar' => 'الإمارات العربية المتحدة', 'nat_en' => 'Emirati', 'nat_ar' => 'إماراتي'],
            ['code' => 'BH', 'phone' => '+973', 'en' => 'Bahrain', 'ar' => 'البحرين', 'nat_en' => 'Bahraini', 'nat_ar' => 'بحريني'],
            ['code' => 'KW', 'phone' => '+965', 'en' => 'Kuwait', 'ar' => 'الكويت', 'nat_en' => 'Kuwaiti', 'nat_ar' => 'كويتي'],
            ['code' => 'OM', 'phone' => '+968', 'en' => 'Oman', 'ar' => 'عمان', 'nat_en' => 'Omani', 'nat_ar' => 'عماني'],
            ['code' => 'QA', 'phone' => '+974', 'en' => 'Qatar', 'ar' => 'قطر', 'nat_en' => 'Qatari', 'nat_ar' => 'قطري'],
            ['code' => 'YE', 'phone' => '+967', 'en' => 'Yemen', 'ar' => 'اليمن', 'nat_en' => 'Yemeni', 'nat_ar' => 'يمني'],
        ];

        $saudiStates = [
            [
                'en' => 'Riyadh Province',
                'ar' => 'منطقة الرياض',
                'cities' => [
                    ['en' => 'Riyadh', 'ar' => 'الرياض'],
                    ['en' => 'Al Kharj', 'ar' => 'الخرج'],
                ]
            ],
            [
                'en' => 'Makkah Province',
                'ar' => 'منطقة مكة المكرمة',
                'cities' => [
                    ['en' => 'Makkah', 'ar' => 'مكة المكرمة'],
                    ['en' => 'Jeddah', 'ar' => 'جدة'],
                    ['en' => 'Taif', 'ar' => 'الطائف'],
                ]
            ],
            [
                'en' => 'Eastern Province',
                'ar' => 'المنطقة الشرقية',
                'cities' => [
                    ['en' => 'Dammam', 'ar' => 'الدمام'],
                    ['en' => 'Khobar', 'ar' => 'الخبر'],
                    ['en' => 'Dhahran', 'ar' => 'الظهران'],
                ]
            ],
            [
                'en' => 'Madinah Province',
                'ar' => 'منطقة المدينة المنورة',
                'cities' => [
                    ['en' => 'Madinah', 'ar' => 'المدينة المنورة'],
                    ['en' => 'Yanbu', 'ar' => 'ينبع'],
                ]
            ],
            [
                'en' => 'Asir Province',
                'ar' => 'منطقة عسير',
                'cities' => [
                    ['en' => 'Abha', 'ar' => 'أبها'],
                    ['en' => 'Khamis Mushait', 'ar' => 'خميس مشيط'],
                ]
            ],
        ];
        // fix: don't use Str::uuid(), use the nextIdentity 
        foreach ($countries as $c) {
            $countryId = Str::uuid()->toString();
            DB::table('countries')->insert([
                'id' => $countryId,
                'code' => $c['code'],
                'phone_code' => $c['phone'],
                'name' => json_encode(['en' => $c['en'], 'ar' => $c['ar']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('nationalities')->insert([
                'id' => Str::uuid()->toString(),
                'country_id' => $countryId,
                'name' => json_encode(['en' => $c['nat_en'], 'ar' => $c['nat_ar']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($c['code'] === 'SA') {
                foreach ($saudiStates as $s) {
                    $stateId = Str::uuid()->toString();
                    DB::table('states')->insert([
                        'id' => $stateId,
                        'country_id' => $countryId,
                        'name' => json_encode(['en' => $s['en'], 'ar' => $s['ar']]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($s['cities'] as $city) {
                        DB::table('cities')->insert([
                            'id' => Str::uuid()->toString(),
                            'country_id' => $countryId,
                            'state_id' => $stateId,
                            'name' => json_encode(['en' => $city['en'], 'ar' => $city['ar']]),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            } elseif ($c['code'] === 'AE') {
                $dubaiStateId = Str::uuid()->toString();
                DB::table('states')->insert([
                    'id' => $dubaiStateId,
                    'country_id' => $countryId,
                    'name' => json_encode(['en' => 'Dubai', 'ar' => 'دبي']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('cities')->insert([
                    'id' => Str::uuid()->toString(),
                    'country_id' => $countryId,
                    'state_id' => $dubaiStateId,
                    'name' => json_encode(['en' => 'Dubai City', 'ar' => 'مدينة دبي']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
