# User Module — What's Needed From Others

## Core Principle

The User module is a **foundation module** with **zero dependencies** on other modules. It does not require, import, or rely on any other module to function.

```

┌─────────────────────────────────────────────────────────────────┐
│ │
│ USER MODULE │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ │ │
│ │ ✅ Requires NOTHING from other modules │ │
│ │ │ │
│ │ ✅ Can be built and tested in isolation │ │
│ │ │ │
│ │ ✅ Provides services to ALL other modules │ │
│ │ │ │
│ └─────────────────────────────────────────────────────────┘ │
│ │
└─────────────────────────────────────────────────────────────────┘
│
│ Provides
│
┌─────────────────────┼─────────────────────┐
│ │ │
▼ ▼ ▼
┌─────────┐ ┌─────────┐ ┌─────────┐
│ Role │ │ Event │ │ Contract│
│ Module │ │Participation│ │ Module │
└─────────┘ └─────────┘ └─────────┘

```

---

## What User Module Needs

### None. Zero. Nothing.

| Category              | Required From Others?                                            |
| --------------------- | ---------------------------------------------------------------- |
| Database tables       | ❌ No                                                            |
| Foreign keys          | ❌ No (users.id is referenced BY others, not referencing others) |
| Services/Repositories | ❌ No                                                            |
| Config values         | ❌ No (all config is self-contained)                             |
| Events                | ❌ No (emits events but doesn't listen to others)                |
| Commands              | ❌ No                                                            |
| Validation rules      | ❌ No                                                            |
| Seed data             | ❌ No (UserSeeder creates its own test data)                     |

---

## Why User Module Has No Dependencies

### 1. Architectural Decision

The User module sits at the **bottom of the dependency graph**. Every other module depends on it, not the other way around.

### 2. Domain Independence

A user exists independently of events, roles, contracts, or tasks. You can have a user without any of those things.

### 3. Practical Implementation

- `users` table has no foreign keys to other tables
- User domain entities don't reference other modules' entities
- User repository doesn't need to join with other tables for basic operations

### 4. Testing Isolation

You can run User module tests without any other module being present:

```bash
# This works even if no other modules exist
php artisan test --filter=UserModuleTest
```

---

## What User Module Provides to Others

Even though User module needs nothing, it **provides critical services** to almost every other module:

### 1. Database Table: `users`

Other modules create foreign keys to `users.id`:

```sql
-- Role module
ALTER TABLE role_user ADD CONSTRAINT fk_role_user_user_id FOREIGN KEY (user_id) REFERENCES users(id);

-- EventParticipation module
ALTER TABLE event_participations ADD CONSTRAINT fk_participations_user_id FOREIGN KEY (user_id) REFERENCES users(id);

-- EventTask module
ALTER TABLE event_tasks ADD CONSTRAINT fk_tasks_assigned_to FOREIGN KEY (assigned_to) REFERENCES users(id);
```

### 2. Domain Repository Interface

Other modules can inject `UserRepositoryInterface` to fetch user data:

```php
// In EventParticipation module
use Modules\User\Domain\Repository\UserRepositoryInterface;

final class CreateParticipationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(CreateParticipationCommand $command): void
    {
        $user = $this->userRepository->findById(new UserId($command->userId));
        // Use user data...
    }
}
```

### 3. Domain Events

Other modules can listen to User module events:

```php
// In EventNotification module
use Modules\User\Domain\Events\UserActivated;

final class SendWelcomeNotification
{
    public function handle(UserActivated $event): void
    {
        // Send SMS/email when user is activated
    }
}
```

### 4. Value Objects

Other modules can use User VOs:

```php
// In EventContract module
use Modules\User\Domain\ValueObject\UserId;

final class Contract
{
    private UserId $userId;  // Reuse UserId VO
}
```

### 5. Presenters

Other modules can format user data consistently:

```php
// In EventParticipation module
use Modules\User\Presentation\Http\Presenter\UserPresenter;

final class ParticipationPresenter
{
    public static function toArray(Participation $participation): array
    {
        return [
            'id' => $participation->getId()->toString(),
            'user' => UserPresenter::toArray($participation->getUser()),
            'event' => EventPresenter::toArray($participation->getEvent()),
        ];
    }
}
```

---

## Contracts/Interfaces User Module Exports

### Repository Interface

```php
// Domain/Repository/UserRepositoryInterface.php
interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findById(UserId $id): ?User;
    public function findByPhone(string $phone): ?User;
    public function findByEmail(string $email): ?User;
    public function findAllActive(): array;
}
```

### Read-Only Repository (For other modules)

```php
// Domain/Repository/UserReadRepositoryInterface.php
interface UserReadRepositoryInterface
{
    public function findById(UserId $id): ?UserData;  // Returns DTO, not domain entity
    public function findByIds(array $ids): array;
    public function exists(UserId $id): bool;
}
```

### Events

```php
// Domain/Events/UserRegistered.php
final class UserRegistered
{
    public function __construct(
        public readonly UserId $userId,
        public readonly string $phone,
        public readonly ?string $email,
        public readonly Carbon $occurredAt
    ) {}
}

// Domain/Events/UserActivated.php
final class UserActivated
{
    public function __construct(
        public readonly UserId $userId,
        public readonly UserId $activatedBy,
        public readonly Carbon $occurredAt
    ) {}
}

// Domain/Events/UserDeactivated.php
// Domain/Events/PhoneVerified.php
// Domain/Events/ProfileUpdated.php
```

---

## What Other Modules Must NOT Assume

When other modules depend on User module, they must NOT assume:

### ❌ That user has employee_profile

```php
// WRONG - This will crash if profile is null
$height = $user->getEmployeeProfile()->getHeight();

// CORRECT - Check for existence
$profile = $user->getEmployeeProfile();
$height = $profile?->getHeight();
```

### ❌ That user has bank_details

```php
// WRONG
$iban = $user->getBankDetails()[0]->getIban();

// CORRECT
$primaryBank = $user->getPrimaryBankDetail();
$iban = $primaryBank?->getIban();
```

### ❌ That user is active

```php
// WRONG - Assume user can log in
if ($user->getEmail() === $request->email) {
    // login
}

// CORRECT - Check active status
if ($user->isActive() && $user->getEmail() === $request->email) {
    // login
}
```

### ❌ That user has verified phone

```php
// WRONG
$user->getPhoneVerifiedAt()->format('Y-m-d');

// CORRECT
$verifiedAt = $user->getPhoneVerifiedAt();
if ($verifiedAt) {
    // phone is verified
}
```

---

## User Module Service Provider Registration

Other modules that need User services must ensure UserServiceProvider is registered **before** their own provider:

```php
// bootstrap/providers.php - CORRECT ORDER
return [
    // User module FIRST (foundation)
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,

    // Shared utilities SECOND
    Modules\Shared\Infrastructure\Providers\SharedServiceProvider::class,

    // Then modules that depend on User
    Modules\Role\Infrastructure\Providers\RoleServiceProvider::class,
    Modules\Event\Infrastructure\Providers\EventServiceProvider::class,
    // ...
];
```

---

## Testing Without Other Modules

User module can be fully tested in isolation:

```php
// tests/Unit/Domain/UserTest.php
namespace Tests\Unit\Domain;

use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\TranslatableText;
use Tests\TestCase;

final class UserTest extends TestCase
{
    /** @test */
    public function user_can_be_created_without_any_other_modules(): void
    {
        // No Role, Event, or Contract modules needed
        $user = User::register(
            id: UserId::generate(),
            name: TranslatableText::fromArray(['ar' => 'محمد', 'en' => 'Mohammed']),
            phone: '+966501234567',
            email: null,
            password: 'hashed_password'
        );

        $this->assertFalse($user->isActive());
        $this->assertNull($user->getEmail());
    }
}
```

---

## Integration Testing (When Other Modules Exist)

Once other modules are built, integration tests verify User module works with them:

```php
// tests/Feature/Integration/UserWithRoleTest.php
namespace Tests\Feature\Integration;

use Modules\User\Domain\User;
use Modules\Role\Domain\Role;
use Tests\TestCase;

final class UserWithRoleTest extends TestCase
{
    /** @test */
    public function user_can_be_assigned_global_role(): void
    {
        // This test requires BOTH User and Role modules
        $user = User::register(...);
        $role = Role::create('system_controller', ...);

        $user->assignRole($role);  // Uses Role module

        $this->assertTrue($user->hasRole('system_controller'));
    }
}
```

---

## Module Independence Checklist

User module maintains independence by:

- [x] No `use` statements importing from other modules
- [x] No database foreign keys to other modules' tables
- [x] No event listeners for other modules' events
- [x] No service provider dependencies on other modules
- [x] No configuration values that require other modules
- [x] No assumptions about existence of other modules' tables

---

## Summary Table

| What User Needs             | Status        |
| --------------------------- | ------------- |
| Other modules' tables       | ❌ Not needed |
| Other modules' repositories | ❌ Not needed |
| Other modules' services     | ❌ Not needed |
| Other modules' events       | ❌ Not needed |
| Other modules' config       | ❌ Not needed |

| What User Provides        | To Whom                                     |
| ------------------------- | ------------------------------------------- |
| `users` table             | All modules with user_id FK                 |
| `UserRepositoryInterface` | Any module needing user data                |
| User domain events        | Any module listening to user changes        |
| `UserId` value object     | Any module needing user identifier          |
| `UserPresenter`           | Any module formatting user in API responses |
