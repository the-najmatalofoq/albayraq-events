<?php
// modules/Currency/Infrastructure/Persistence/Eloquent/Seeders/CurrencySeeder.php
declare(strict_types=1);

namespace Modules\Currency\Infrastructure\Persistence\Eloquent\Seeders;

use Illuminate\Database\Seeder;
use Modules\Currency\Infrastructure\Persistence\Eloquent\Models\CurrencyModel;
use Illuminate\Support\Str;

final class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'id' => (string) Str::uuid(),
                'name' => ['en' => 'Saudi Riyal', 'ar' => 'ريال سعودي'],
                'code' => 'SAR',
                'symbol' => 'ر.س',
                'is_active' => true,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => ['en' => 'US Dollar', 'ar' => 'دولار أمريكي'],
                'code' => 'USD',
                'symbol' => '$',
                'is_active' => true,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => ['en' => 'Euro', 'ar' => 'يورو'],
                'code' => 'EUR',
                'symbol' => '€',
                'is_active' => true,
            ],
        ];

        foreach ($currencies as $currency) {
            CurrencyModel::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
