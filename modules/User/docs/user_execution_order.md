# User Module Execution Order

## Build Sequence Position

The User module is **Wave 1, Position #1** — the **very first** module to be built and migrated in the entire system.

```
BUILD ORDER (Top to Bottom)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

▶ WAVE 1 - FOUNDATION (Starts here)
│
├── 1. USER MODULE ◀━━ YOU ARE HERE
│   ├── users table
│   ├── employee_profiles table
│   ├── contact_phones table
│   └── bank_details table
│
├── 2. Role Module
│   ├── roles table
│   └── role_user pivot
│
├── 3. Shared Module (attachments)
│
├── 4. ViolationType Module
│
├── 5. ContractRejectionReason Module
│
└── 6. ReportType Module

▶ WAVE 2 - USER PROFILE + EVENTS
│
├── 7. Event Module
├── 8. WorkSchedule Module
│
▶ WAVE 3 - EVENT STAFFING
│
├── 9. EventStaffingPosition Module
├── 10. Quiz Module
├── 11. EventStaffingGroup Module
├── 12. EventRoleAssignment Module
├── 13. EventRoleCapability Module
│
▶ WAVE 4 - APPLICATIONS
│
├── 14. Question Module
├── 15. EventPositionApplication Module
│
▶ WAVE 5 - PARTICIPATION
│
├── 16. EventParticipation Module
│
▶ WAVE 6 - OPERATIONS
│
├── 17. DigitalSignature Module
├── 18. EventContract Module
├── 19. ContractAcceptanceStep Module
├── 20. EmployeeQuizAttempt Module
├── 21. EventAttendance Module
├── 22. AttendanceBarcode Module
├── 23. ParticipationEvaluation Module
├── 24. ParticipationViolation Module
├── 25. Discount Module
├── 26. EventParticipationBadge Module
├── 27. EventExperienceCertificate Module
│
▶ WAVE 7 - EVENT OPERATIONS
│
├── 28. EventTask Module
├── 29. EventOperationalReport Module
├── 30. EventAssetCustody Module
├── 31. EventExpense Module
└── 32. EventAnnouncement Module
```

---

## Why User Module First?

### 1. Zero External Dependencies

The `users` table has no foreign keys to any other table. It stands alone.

### 2. Required by EVERY Other Module

- **Authentication** needs users to verify credentials
- **Role assignment** needs users to assign roles to
- **Event participation** needs users to link to events
- **Contracts** need users to sign
- **Tasks** need users to assign to
- **Announcements** need users to send/receive

### 3. Foundation for All Relationships

```
users.id appears as foreign key in:
- employee_profiles.user_id
- contact_phones.user_id
- bank_details.user_id
- role_user.user_id
- event_role_assignments.user_id
- event_participations.user_id
- event_tasks.assigned_to
- event_announcements.sender_id
- attachments.uploaded_by
- digital_signatures.user_id
- employee_quiz_attempts.user_id
- event_expenses.submitted_by
... and 15+ more
```

### 4. Cannot Migrate Other Modules First

If you try to migrate any module that references `users.id` before the users table exists, you get:

```
SQLSTATE[HY000]: General error: 1824 Failed to open the referenced table 'users'
```

---

## Build Order Within User Module

Even within the User module, migrations must run in specific order:

```
┌─────────────────────────────────────────────────────────────┐
│                    USER MODULE BUILD ORDER                   │
└─────────────────────────────────────────────────────────────┘

STEP 1: Create users table (Wave 1, #1)
        ↓
        No dependencies. Safe to run first.

STEP 2: Create employee_profiles table (Wave 2, #8)
        ↓
        Depends ON: users table
        Foreign key: employee_profiles.user_id → users.id

STEP 3: Create contact_phones table (Wave 2, #9)
        ↓
        Depends ON: users table
        Foreign key: contact_phones.user_id → users.id

STEP 4: Create bank_details table (Wave 2, #10)
        ↓
        Depends ON: users table
        Foreign key: bank_details.user_id → users.id
```

**Note:** The gap between Step 1 and Steps 2-4 is because Wave 1 runs all foundation tables from all modules first, then Wave 2 runs all profile/event tables.

---

## Execution Commands in Order

### Step 1: Create users table

```bash
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100000_create_users_table.php
```

### Step 2: Run remaining Wave 1 migrations from other modules

```bash
php artisan migrate
# This runs all Wave 1 migrations in timestamp order
```

### Step 3: Create employee_profiles table

```bash
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100100_create_employee_profiles_table.php
```

### Step 4: Create contact_phones table

```bash
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100200_create_contact_phones_table.php
```

### Step 5: Create bank_details table

```bash
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100300_create_bank_details_table.php
```

---

## Fresh Install Execution

When running `php artisan migrate:fresh`, Laravel executes migrations in timestamp order across ALL modules:

```
2026_03_25_100000_create_users_table.php          ← User module
2026_03_25_100001_create_roles_table.php          ← Role module
2026_03_25_100002_create_role_user_table.php      ← Role module
2026_03_25_100050_create_attachments_table.php    ← Shared module
2026_03_25_101000_create_violation_types_table.php ← ViolationType
2026_03_25_101500_create_contract_rejection_reasons_table.php
2026_03_25_102000_create_report_types_table.php   ← ReportType
2026_03_25_100100_create_employee_profiles_table.php ← User module
2026_03_25_100200_create_contact_phones_table.php    ← User module
2026_03_25_100300_create_bank_details_table.php      ← User module
2026_03_25_102500_create_events_table.php         ← Event module
... and so on
```

---

## What Can and Cannot Run Before User Module

### Can Run Before (No Dependencies)

- **None.** User module is first. No other module should run before it.

### Cannot Run Before (Depends on users)

- Role module (role_user.user_id → users.id)
- EventRoleAssignment module
- EventParticipation module
- EventContract module
- EventAttendance module
- EventTask module
- EventAnnouncement module
- DigitalSignature module
- EmployeeQuizAttempt module
- EventExpense module
- FileAttachment module (uploaded_by → users.id)
- Any module with user_id foreign key

---

## Service Provider Registration Order

In `bootstrap/providers.php`, UserServiceProvider should be registered early:

```php
return [
    // Foundation modules first
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,
    Modules\Shared\Infrastructure\Providers\SharedServiceProvider::class,

    // Then modules that depend on User
    Modules\Role\Infrastructure\Providers\RoleServiceProvider::class,
    Modules\Event\Infrastructure\Providers\EventServiceProvider::class,

    // Then the rest...
];
```

---

## Seeding Order

Seeders must run after migrations and respect dependencies:

```bash
# Step 1: Seed users first (so users exist for role assignment)
php artisan db:seed --class=Modules\\User\\Infrastructure\\Persistence\\Seeders\\UserSeeder

# Step 2: Seed roles (requires users to exist for pivot table)
php artisan db:seed --class=Modules\\Role\\Infrastructure\\Persistence\\Seeders\\RoleSeeder

# Step 3: Seed events (requires users for created_by field)
php artisan db:seed --class=Modules\\Event\\Infrastructure\\Persistence\\Seeders\\EventSeeder

# Step 4: Seed participations (requires users and events)
php artisan db:seed --class=Modules\\EventParticipation\\Infrastructure\\Persistence\\Seeders\\EventParticipationSeeder
```

---

## Testing Execution Order

When running tests, the `RefreshDatabase` trait runs migrations in the correct order automatically. However, for feature tests that require specific user states:

```php
namespace Tests\Feature\User;

use Tests\TestCase;

class UserModuleTest extends TestCase
{
    // This runs migrations before each test
    use RefreshDatabase;

    /** @test */
    public function user_registration_works()
    {
        // User table must exist first
        $response = $this->postJson('/api/v1/users/register', [
            'name' => ['ar' => 'اختبار', 'en' => 'Test'],
            'phone' => '+966501234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
    }
}
```

---

## Common Build Order Mistakes

### ❌ Mistake 1: Running employee_profiles migration first

```bash
# WRONG ORDER
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100100_create_employee_profiles_table.php
```

**Error:** `SQLSTATE[HY000]: General error: 1824 Failed to open the referenced table 'users'`

### ❌ Mistake 2: Running Role module before User module

```bash
# WRONG ORDER
php artisan migrate --path=modules/Role/Infrastructure/Persistence/Migrations/2026_03_25_100001_create_roles_table.php
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100000_create_users_table.php
```

**Error:** No error initially, but when role_user pivot tries to reference users.id, the table doesn't exist yet.

### ✅ Correct Order

```bash
# ALWAYS run users table first
php artisan migrate --path=modules/User/Infrastructure/Persistence/Migrations/2026_03_25_100000_create_users_table.php

# Then everything else
php artisan migrate
```

---

## Rollback Order

Rollbacks happen in **reverse** of migration order:

```
Rollback Order (First to Last):
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1. Wave 7 migrations (event_announcements, event_expenses, etc.)
2. Wave 6 migrations
3. Wave 5 migrations (event_participations)
4. Wave 4 migrations
5. Wave 3 migrations
6. Wave 2 migrations (bank_details, contact_phones, employee_profiles)
7. Wave 1 migrations (users) ← LAST to roll back
```

```bash
# This rolls back the LAST migration first
php artisan migrate:rollback

# To roll back User module migrations specifically:
php artisan migrate:rollback --path=modules/User/Infrastructure/Persistence/Migrations/
# Rolls back in reverse: bank_details, contact_phones, employee_profiles, then users
```

---

## Dependency Validation Script

Before building other modules, verify User module is complete:

```bash
#!/bin/bash
# check_user_module.sh

echo "Checking User module migrations..."
if php artisan migrate:status | grep -q "users.*Yes"; then
    echo "✅ users table exists"
else
    echo "❌ users table missing - run User module migrations first"
    exit 1
fi

if php artisan migrate:status | grep -q "employee_profiles.*Yes"; then
    echo "✅ employee_profiles table exists"
else
    echo "⚠️  employee_profiles table missing (optional for some features)"
fi

echo "User module ready. Proceed with other modules."
```

---

## Summary Checklist

Before moving to next module (Role), ensure:

- [ ] `2026_03_25_100000_create_users_table.php` has run successfully
- [ ] `users` table exists in database
- [ ] `UserSeeder` has seeded at least one active user
- [ ] `UserServiceProvider` is registered in `bootstrap/providers.php`
- [ ] User authentication works (can register, login with JWT)
- [ ] No foreign key errors when querying users
