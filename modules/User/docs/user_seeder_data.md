# User Module Seeder Data

## Seeder Class

`Modules\User\Infrastructure\Persistence\Seeders\UserSeeder`

## Execution Order

Run **before** RoleSeeder (so users exist for role assignment)

## Seed Data Purpose

Create baseline users for development, testing, and initial deployment.

---

## Users Created by Seeder

| #    | Name (ar/en)                       | Phone                          | Email                                           | Role               | Active | Verified |
| ---- | ---------------------------------- | ------------------------------ | ----------------------------------------------- | ------------------ | ------ | -------- |
| 1    | مدير النظام / System Controller    | +966500000001                  | admin@system.com                                | system_controller  | Yes    | Yes      |
| 2    | المدير العام / General Manager     | +966500000002                  | gm@system.com                                   | general_manager    | Yes    | Yes      |
| 3    | مدير العمليات / Operations Manager | +966500000003                  | ops@system.com                                  | operations_manager | Yes    | Yes      |
| 4-13 | موظف 1-10 / Employee 1-10          | +966500000004 to +966500000013 | employee1@example.com to employee10@example.com | individual         | Yes    | No       |

---

## Profile Data (for Employee 1 as example)

| Field             | Value                     |
| ----------------- | ------------------------- |
| full_name (ar/en) | أحمد محمد / Ahmed Mohamed |
| birth_date        | 1990-05-15                |
| nationality       | SA                        |
| gender            | male                      |
| height            | 175 cm                    |
| weight            | 72 kg                     |

---

## Contact Phone (for Employee 1)

| Field        | Value         |
| ------------ | ------------- |
| name         | Mohamed Ahmed |
| phone        | +966501234568 |
| relationship | Father        |

---

## Bank Details (for Employee 1)

| Field              | Value                    |
| ------------------ | ------------------------ |
| account_owner_name | Ahmed Mohamed            |
| bank_name          | Al Rajhi Bank            |
| iban               | SA4400000001234567891234 |
| is_primary         | true                     |

---

## Password Convention

All seeded users use same password for development:

- **Password:** `Admin@123` (for admin/manager accounts)
- **Password:** `password` (for employee accounts)

**⚠️ Production:** Never use seeded passwords. Run seeder with `--env=production` flag to generate random passwords.

---

## Running Seeder

```bash
# Development
php artisan db:seed --class=Modules\\User\\Infrastructure\\Persistence\\Seeders\\UserSeeder

# Production (skip or use random passwords)
php artisan db:seed --class=Modules\\User\\Infrastructure\\Persistence\\Seeders\\UserSeeder --env=production
```

---

## Seeder Dependencies

| Required Before | Required After                            |
| --------------- | ----------------------------------------- |
| None            | RoleSeeder (assigns roles to these users) |

---

## Testing Helper Methods

Seeders provide factory methods for tests:

- `UserSeeder::createAdmin()` → returns activated system_controller
- `UserSeeder::createManager()` → returns activated general_manager
- `UserSeeder::createWorker($overrides)` → returns individual user with optional overrides

---

## Cleanup

Seeder is idempotent:

- Checks for existing phone numbers before inserting
- Updates existing users instead of duplicate insertion
- Can be run multiple times safely

```

```
