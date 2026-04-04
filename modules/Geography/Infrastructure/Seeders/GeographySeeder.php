<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Seeders;

use Illuminate\Database\Seeder;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\{
    CountryModel,
};


final class GeographySeeder extends Seeder
{
    public function run(): void
    {
        $countries = config('geography-seed.countries', []);
        $statesConfig = config('geography-seed.states', []);

        foreach ($countries as $c) {
            $country = CountryModel::query()->create([
                'code' => $c['code'],
                'phone_code' => $c['phone'],
                'name' => ['en' => $c['en'], 'ar' => $c['ar']],
                'is_active' => true,
            ]);

            NationalityModel::query()->create([
                'country_id' => $country->id,
                'name' => ['en' => $c['nat_en'], 'ar' => $c['nat_ar']],
                'is_active' => true,
            ]);

            if (isset($statesConfig[$c['code']])) {
                foreach ($statesConfig[$c['code']] as $s) {
                    $state = StateModel::query()->create([
                        'country_id' => $country->id,
                        'name' => ['en' => $s['en'], 'ar' => $s['ar']],
                    ]);

                    if (isset($s['cities'])) {
                        foreach ($s['cities'] as $city) {
                            CityModel::query()->create([
                                'country_id' => $country->id,
                                'state_id' => $state->id,
                                'name' => ['en' => $city['en'], 'ar' => $city['ar']],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
