<?php
// src\database\seeders\DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Role\Infrastructure\Persistence\Seeders\RoleSeeder;
use Modules\User\Infrastructure\Persistence\Seeders\UserSeeder;
use Modules\Geography\Infrastructure\Persistence\Seeders\NationalitySeeder;
use Modules\ReportType\Infrastructure\Persistence\Seeders\ReportTypeSeeder;

use Modules\ViolationType\Infrastructure\Persistence\Seeders\ViolationTypeSeeder;
use Modules\ContractRejectionReason\Infrastructure\Persistence\Seeders\ContractRejectionReasonSeeder;
use Modules\User\Infrastructure\Persistence\Seeders\EmployeeSeeder;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Seeders\EventRoleAssignmentSeeder;
use Modules\EventRoleCapability\Infrastructure\Persistence\Seeders\EventRoleCapabilitySeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            NationalitySeeder::class,
            ReportTypeSeeder::class,
            ViolationTypeSeeder::class,
            ContractRejectionReasonSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
            EventRoleAssignmentSeeder::class,
            EventRoleCapabilitySeeder::class,
        ]);
    }
}
