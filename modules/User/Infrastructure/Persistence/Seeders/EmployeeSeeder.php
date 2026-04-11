<?php

namespace Modules\User\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\ContactPhoneModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\BankDetailModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\MedicalRecordModel;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employeeRole = RoleModel::where('slug', RoleSlugEnum::EMPLOYEE->value)->first();

        if (!$employeeRole) {
            $this->command->error('Employee role not found. Please run RoleSeeder first.');
            return;
        }

        // Create 20 employees
        UserModel::factory(20)->create()->each(function (UserModel $user) use ($employeeRole) {
            // Assign role
            $user->roles()->attach($employeeRole->id);

            // Create Profile
            EmployeeProfileModel::factory()->create([
                'user_id' => $user->id,
                'full_name' => $user->name,
            ]);

            // Create 1-2 Contact Phones
            ContactPhoneModel::factory(rand(1, 2))->create([
                'user_id' => $user->id,
            ]);

            // Create Bank Detail
            $account_owner = $user->name;
            if ($account_owner instanceof \Modules\Shared\Domain\ValueObject\TranslatableText) {
                $account_owner = $account_owner->getFor('en');
            } elseif (is_array($account_owner)) {
                $account_owner = $account_owner['en'] ?? $account_owner['ar'] ?? '';
            }

            BankDetailModel::factory()->create([
                'user_id' => $user->id,
                'account_owner' => $account_owner,
            ]);

            // Create Medical Record
            MedicalRecordModel::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
