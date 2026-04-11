<?php
// src\database\seeders\DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Role\Infrastructure\Persistence\Seeders\RoleSeeder;
use Modules\User\Infrastructure\Persistence\Seeders\UserSeeder;
use Modules\Geography\Infrastructure\Persistence\Seeders\NationalitySeeder;
use Modules\ReportType\Infrastructure\Persistence\Seeders\ReportTypeSeeder;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Seeders\EventRoleAssignmentSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            NationalitySeeder::class,
            ReportTypeSeeder::class,
            UserSeeder::class,
            EventRoleAssignmentSeeder::class,
        ]);
    }
}
