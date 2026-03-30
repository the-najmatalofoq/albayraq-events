# User Module Migration Details

## Migration Wave Placement

The User module spans **two migration waves** because of dependencies:

| Wave   | Position | Migration                                              | Table             | Depends On |
| ------ | -------- | ------------------------------------------------------ | ----------------- | ---------- |
| Wave 1 | #1       | `2026_03_25_100000_create_users_table.php`             | users             | None       |
| Wave 2 | #8       | `2026_03_25_100100_create_employee_profiles_table.php` | employee_profiles | users      |
| Wave 2 | #9       | `2026_03_25_100200_create_contact_phones_table.php`    | contact_phones    | users      |
| Wave 2 | #10      | `2026_03_25_100300_create_bank_details_table.php`      | bank_details      | users      |

## Why Two Waves?

**Wave 1** must run first because:

- `users` table has no foreign keys to any other table
- Other modules (Role, EventParticipation, EventContract) depend on `users.id`
- The authentication system needs `users` table to function

**Wave 2** runs after `users` exists because:

- All three tables have `user_id` foreign keys referencing `users.id`
- Cannot create foreign key to non-existent table

## File Naming Convention

All migration files follow Laravel's timestamp format:

```

YYYY_MM_DD_HHMMSS_description.php

```

Where:

- `YYYY_MM_DD` = Date of creation (2026_03_25 for all core migrations)
- `HHMMSS` = 6-digit timestamp (100000, 100100, 100200, 100300)
- `description` = Snake_case table name with "create" prefix

## Migration #1: users Table

**File:** `2026_03_25_100000_create_users_table.php`

**Purpose:** Create the core authentication table that stores all user accounts.

**Key Features:**

- UUID primary key (no auto-incrementing integers)
- Soft deletes (`deleted_at` column)
- Timestamps (`created_at`, `updated_at`)
- Unique constraints on `phone`, `email`, `national_id`
- JSON column for multi-language `name` field

**Irreversible Action:** Dropping this table will cascade-delete all related records in 25+ other tables.

**Rollback Risk:** High — affects entire system. Never roll back in production.

## Migration #8: employee_profiles Table

**File:** `2026_03_25_100100_create_employee_profiles_table.php`

**Purpose:** Store extended personal information for workers.

**Key Features:**

- One-to-one relationship with users (`user_id` unique constraint)
- Cascade delete: when user is deleted, profile is auto-deleted
- Nullable fields for progressive profile completion
- Medical record as TEXT (unlimited length)

**Dependency:** Must run AFTER users table exists.

**Foreign Key:** `user_id` references `users.id` with `ON DELETE CASCADE`

## Migration #9: contact_phones Table

**File:** `2026_03_25_100200_create_contact_phones_table.php`

**Purpose:** Store emergency contact persons for users.

**Key Features:**

- One-to-many relationship (user can have multiple contacts)
- Cascade delete: when user is deleted, contacts are auto-deleted
- Relationship field limited to predefined values (Father, Mother, etc.)

**Dependency:** Must run AFTER users table exists.

**Foreign Key:** `user_id` references `users.id` with `ON DELETE CASCADE`

## Migration #10: bank_details Table

**File:** `2026_03_25_100300_create_bank_details_table.php`

**Purpose:** Store bank account information for payroll.

**Key Features:**

- One-to-many relationship (user can have multiple accounts)
- Cascade delete: when user is deleted, bank details are auto-deleted
- `is_primary` boolean flag for payroll selection
- IBAN validation (format check, not actual account verification)

**Dependency:** Must run AFTER users table exists.

**Foreign Key:** `user_id` references `users.id` with `ON DELETE CASCADE`

**Business Rule:** The application layer must ensure only one `is_primary = true` per user (partial unique index recommended but not mandatory at DB level).

## Migration Execution Commands

### Run all User module migrations

```bash
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/
```

### Run specific migration by order

```bash
# First, run users table
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100000_create_users_table.php

# Then run dependent tables
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100100_create_employee_profiles_table.php
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100200_create_contact_phones_table.php
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100300_create_bank_details_table.php
```

### Rollback User module migrations

```bash
# Rollback all User migrations (in reverse order)
php artisan migrate:rollback --path=modules/User/Infrastructure/Persistence/Migrations/

# Rollback specific migration
php artisan migrate:rollback --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100300_create_bank_details_table.php
```

## Migration Dependencies Graph

```
                    ┌─────────────────┐
                    │  Wave 1, #1     │
                    │  users          │
                    └────────┬────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
    ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
    │ Wave 2, #8  │  │ Wave 2, #9  │  │ Wave 2, #10 │
    │ employee_   │  │ contact_    │  │ bank_       │
    │ profiles    │  │ phones      │  │ details     │
    └─────────────┘  └─────────────┘  └─────────────┘
```

## State After Migration

### Before running migrations

- No tables exist
- Cannot register users
- Authentication fails

### After running Migration #1 (users)

- Users can register
- Users can log in (if activated)
- No profile, contacts, or bank details yet

### After running Migrations #8, #9, #10

- Users can complete their profiles
- Emergency contacts can be stored
- Bank accounts can be added
- System ready for payroll integration

## Fresh Installation Order

When running `php artisan migrate:fresh`, migrations execute in this order:

1. All Wave 1 migrations from all modules (including users table)
2. All Wave 2 migrations from all modules (including employee_profiles, contact_phones, bank_details)
3. Waves 3-7 follow

## Troubleshooting

### Error: "Base table or view not found"

**Cause:** Trying to create employee_profiles before users table exists.
**Solution:** Ensure migrations run in correct wave order.

### Error: "Cannot add foreign key constraint"

**Cause:** users table missing or wrong column type.
**Solution:** Verify users.id is UUID and exists before dependent migrations.

### Error: "Duplicate column name"

**Cause:** Migration already ran partially.
**Solution:** Run `php artisan migrate:rollback` then `php artisan migrate`.

### Error: "JSON column not supported"

**Cause:** MySQL version < 5.7.8 or MariaDB < 10.2.7.
**Solution:** Upgrade database to version with JSON support.

## Migration File Template

For creating new User module migrations in the future:

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateExampleTable extends Migration
{
    public function up(): void
    {
        Schema::create('example', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('field_name');
            $table->timestamps();

            // Add indexes for foreign keys and frequently queried columns
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('example');
    }
}
```

## Testing Migrations

```bash
# Run migrations in test environment
php artisan migrate --env=testing

# Rollback and remigrate
php artisan migrate:refresh --env=testing

# Reset completely
php artisan migrate:fresh --env=testing
```

## Production Considerations

- **Never** roll back migrations in production without backup
- **Always** test migrations on staging first
- **Use** database transactions for safety (Laravel wraps migrations automatically)
- **Monitor** migration time for users table (indexes on large tables can take minutes)
- **Consider** zero-downtime deployment: run migrations before code deployment
