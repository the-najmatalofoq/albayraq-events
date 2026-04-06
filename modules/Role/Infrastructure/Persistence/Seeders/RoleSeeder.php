<?php

declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Infrastructure\Persistence\Eloquent\RoleModel;
use Modules\Role\Domain\Enum\RoleSlugEnum;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'slug' => RoleSlugEnum::SYSTEM_CONTROLLER->value,
                'name' => ['en' => 'System Controller', 'ar' => 'مسؤول النظام'],
            ],
            [
                'slug' => RoleSlugEnum::GENERAL_MANAGER->value,
                'name' => ['en' => 'General Manager', 'ar' => 'مدير عام'],
            ],
            [
                'slug' => RoleSlugEnum::OPERATIONS_MANAGER->value,
                'name' => ['en' => 'Operations Manager', 'ar' => 'مدير العمليات'],
            ],
            [
                'slug' => RoleSlugEnum::PROJECT_MANAGER->value,
                'name' => ['en' => 'Project Manager', 'ar' => 'مدير المشروع'],
            ],
            [
                'slug' => RoleSlugEnum::AREA_MANAGER->value,
                'name' => ['en' => 'Area Manager', 'ar' => 'مدير المنطقة'],
            ],
            [
                'slug' => RoleSlugEnum::SITE_MANAGER->value,
                'name' => ['en' => 'Site Manager', 'ar' => 'مدير الموقع'],
            ],
            [
                'slug' => RoleSlugEnum::SUPERVISOR->value,
                'name' => ['en' => 'Supervisor', 'ar' => 'مشرف'],
            ],
            [
                'slug' => RoleSlugEnum::INDIVIDUAL->value,
                'name' => ['en' => 'Individual', 'ar' => 'فرد'],
            ],
            [
                'slug' => RoleSlugEnum::ADMISSIONS_ADMIN->value,
                'name' => ['en' => 'Admissions Admin', 'ar' => 'مسؤول قبول'],
            ],
            [
                'slug' => RoleSlugEnum::EMPLOYEE->value,
                'name' => ['en' => 'Employee', 'ar' => 'موظف'],
            ],
        ];

        foreach ($roles as $roleData) {
            $slug = RoleSlugEnum::from($roleData['slug']);

            RoleModel::updateOrCreate(
                ['slug' => $slug->value],
                [
                    'name'      => $roleData['name'],
                    'is_global' => $slug->isGlobal(),
                    'level'     => $slug->level()->value,
                ]
            );
        }
    }
}
