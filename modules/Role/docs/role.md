# Role Module

## Module Purpose

The Role module manages global roles that span across the entire system, not scoped to specific events. There are three global roles: **system_controller** (god mode), **general_manager** (sees all events and reports), and **operations_manager** (sees all events and reports). All other roles (project_manager, area_manager, site_manager, supervisor, individual, admissions_admin) are event-scoped and managed by the EventRoleAssignment module — NOT this module.

This module handles role definitions, role-user assignments (pivot table), and provides authorization checks for global permissions. It is a catalog module — roles are seeded, not created via API during normal operation.

---

## Table Schema

### `roles`

| Column     | Type      | Constraints      | Description                                                                              |
| ---------- | --------- | ---------------- | ---------------------------------------------------------------------------------------- |
| id         | uuid      | PRIMARY KEY      | Auto-generated UUID                                                                      |
| slug       | string    | UNIQUE, NOT NULL | Machine-readable identifier (system_controller, general_manager, operations_manager)     |
| name       | json      | NOT NULL         | Human-readable name in Arabic/English `{"ar": "مدير النظام", "en": "System Controller"}` |
| is_global  | boolean   | DEFAULT: false   | True for global roles (3 roles), false for event-scoped roles                            |
| level      | string    | NOT NULL         | Hierarchy level: system, executive, project, area, site, supervisor, individual          |
| created_at | timestamp | NOT NULL         |                                                                                          |
| updated_at | timestamp | NOT NULL         |                                                                                          |

**Level values and order (lowest to highest):**
individual (1) → supervisor (2) → site_manager (3) → area_manager (4) → project_manager (5) → executive (6) → system (7)

### `role_user` (pivot)

| Column  | Type | Constraints                            | Description                       |
| ------- | ---- | -------------------------------------- | --------------------------------- |
| user_id | uuid | FOREIGN KEY → users.id, CASCADE DELETE | User receiving the role           |
| role_id | uuid | FOREIGN KEY → roles.id, CASCADE DELETE | Role being assigned               |
|         |      | PRIMARY KEY: (user_id, role_id)        | Composite key prevents duplicates |

No timestamps on pivot table. No additional columns.

---

## Migration Details

| Migration File                                 | Wave   | Order | Dependencies                                  |
| ---------------------------------------------- | ------ | ----- | --------------------------------------------- |
| `2026_03_25_100001_create_roles_table.php`     | Wave 1 | #2    | None                                          |
| `2026_03_25_100002_create_role_user_table.php` | Wave 1 | #3    | users (from User module), roles (this module) |

**Critical:** Must run AFTER users table (Wave 1, #1) but can run before User module's other tables.

---

## Relations

### Internal

- `role_user.role_id` → `roles.id` (CASCADE DELETE)
- `role_user.user_id` → `users.id` (CASCADE DELETE) — crosses to User module

### External (from other modules)

None. Event-scoped roles are NOT stored here.

### Eloquent Relationships (in UserModel)

```php
// In UserModel (User module)
public function roles(): BelongsToMany
{
    return $this->belongsToMany(RoleModel::class, 'role_user');
}
```

---

## Execution Order

**Build Sequence Position:** Wave 1, #2 and #3 — immediately after User module's users table.

```
Wave 1:
  #1: users table (User module) ← MUST EXIST FIRST
  #2: roles table (Role module) ← CAN RUN
  #3: role_user table (Role module) ← DEPENDS ON BOTH #1 AND #2
```

**Cannot build role_user table before users table exists.**

**Service Provider Registration:** Must be registered AFTER UserServiceProvider in `bootstrap/providers.php`:

```php
return [
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,
    Modules\Role\Infrastructure\Providers\RoleServiceProvider::class,  // HERE
    // ... other modules
];
```

---

## What's Needed From Others

### From User Module (Required)

| Need                      | Purpose                                                 |
| ------------------------- | ------------------------------------------------------- |
| `users` table             | role_user.user_id foreign key                           |
| `UserRepositoryInterface` | To fetch users when assigning roles                     |
| `User` domain entity      | To add assignRole() method to User aggregate            |
| `UserRegistered` event    | To automatically assign INDIVIDUAL role on registration |

### What Role Module Provides to Others

| Provides                  | To Whom                        | Purpose                        |
| ------------------------- | ------------------------------ | ------------------------------ |
| `RoleRepositoryInterface` | Any module needing role lookup | Check user roles               |
| `HasRoles` trait          | User module (via User entity)  | Role assignment methods        |
| `roles` table             | EventRoleAssignment module     | Reference for role definitions |
| Authorization middleware  | Entire application             | `role:system_controller` guard |

---

## Domain Entities

### Aggregate Root: `Role`

- **Identity:** RoleId (UUID)
- **Slug:** String — unique, machine-readable (system_controller, general_manager, operations_manager)
- **Name:** TranslatableText — Arabic/English display name
- **IsGlobal:** Boolean — true only for the 3 global roles
- **Level:** Enum — hierarchy level (system, executive, project, area, site, supervisor, individual)

### Value Object: `RoleLevel` (Enum)

Ordered hierarchy for permission inheritance:

- INDIVIDUAL (level 1) — lowest, default for workers
- SUPERVISOR (level 2)
- SITE_MANAGER (level 3)
- AREA_MANAGER (level 4)
- PROJECT_MANAGER (level 5)
- EXECUTIVE (level 6) — general_manager, operations_manager
- SYSTEM (level 7) — system_controller only

### Repository Interface: `RoleRepositoryInterface`

- `save(Role $role): void`
- `findById(RoleId $id): ?Role`
- `findBySlug(string $slug): ?Role`
- `findAllGlobal(): array`
- `assignToUser(RoleId $roleId, UserId $userId): void`
- `revokeFromUser(RoleId $roleId, UserId $userId): void`
- `getUserRoles(UserId $userId): array`

### Domain Events

- `RoleAssigned` — When role is assigned to user (payload: user_id, role_id, assigned_by)
- `RoleRevoked` — When role is removed from user (payload: user_id, role_id, revoked_by)

---

## CQRS Commands

### Commands (Write)

| Command              | Input                           | Behavior                           |
| -------------------- | ------------------------------- | ---------------------------------- |
| `AssignRoleToUser`   | user_id, role_slug, assigned_by | Adds entry to role_user pivot      |
| `RevokeRoleFromUser` | user_id, role_slug, revoked_by  | Removes entry from role_user pivot |

### Queries (Read)

| Query               | Input              | Output              |
| ------------------- | ------------------ | ------------------- |
| `GetUserRoles`      | user_id            | Array of role slugs |
| `CheckUserHasRole`  | user_id, role_slug | Boolean             |
| `ListUsersWithRole` | role_slug          | Paginated user list |

**Note:** No CreateRole, UpdateRole, DeleteRole commands. Roles are seeded, not modified at runtime.

---

## API Endpoints

Base path: `/api/v1/roles`

| Method | URI               | Action                   | Auth     | Roles Allowed                            |
| ------ | ----------------- | ------------------------ | -------- | ---------------------------------------- |
| GET    | `/`               | ListRolesAction          | Required | system_controller, general_manager       |
| GET    | `/user/{user_id}` | GetUserRolesAction       | Required | Self, system_controller, general_manager |
| POST   | `/assign`         | AssignRoleToUserAction   | Required | system_controller only                   |
| DELETE | `/revoke`         | RevokeRoleFromUserAction | Required | system_controller only                   |

### Request/Response Examples

**POST /assign**

```json
{
    "user_id": "550e8400-e29b-41d4-a716-446655440000",
    "role_slug": "general_manager"
}
```

Response: 200 with `{"message": "Role assigned successfully"}`

**GET /user/{user_id}**
Response:

```json
{
    "user_id": "550e8400-e29b-41d4-a716-446655440000",
    "roles": [
        { "slug": "individual", "name": { "ar": "فرد", "en": "Individual" } },
        {
            "slug": "general_manager",
            "name": { "ar": "المدير العام", "en": "General Manager" }
        }
    ]
}
```

---

## Presenters API Response Format

### RolePresenter

```json
{
    "id": "uuid",
    "slug": "system_controller",
    "name": { "ar": "مدير النظام", "en": "System Controller" },
    "level": "system",
    "is_global": true
}
```

### RoleAssignmentPresenter (for role_user pivot)

```json
{
    "user_id": "uuid",
    "role": {...}  // RolePresenter embedded
}
```

---

## Seeder Data

### RoleSeeder

Seeds the 3 global roles:

| Slug               | Name (ar)     | Name (en)          | Level     | Is Global |
| ------------------ | ------------- | ------------------ | --------- | --------- |
| system_controller  | مدير النظام   | System Controller  | system    | true      |
| general_manager    | المدير العام  | General Manager    | executive | true      |
| operations_manager | مدير العمليات | Operations Manager | executive | true      |

Also seeds the 6 event-scoped role definitions (for reference, though assignments go to EventRoleAssignment module):

| Slug             | Name (ar)    | Name (en)        | Level      | Is Global |
| ---------------- | ------------ | ---------------- | ---------- | --------- |
| project_manager  | مدير المشروع | Project Manager  | project    | false     |
| area_manager     | مدير المنطقة | Area Manager     | area       | false     |
| site_manager     | مدير الموقع  | Site Manager     | site       | false     |
| supervisor       | مشرف         | Supervisor       | supervisor | false     |
| individual       | فرد          | Individual       | individual | false     |
| admissions_admin | مسؤول القبول | Admissions Admin | executive  | false     |

**Run order:** After UserSeeder (so users exist for pivot assignments)

---

## Infrastructure Implementation

### Eloquent Models

**RoleModel:**

- Table: `roles`
- Casts: `name` → array, `is_global` → boolean
- Relationships: `users()` → BelongsToMany through `role_user`

**RoleUserPivot:**

- No model needed (Laravel handles pivot automatically)

### EloquentRoleRepository

Implements RoleRepositoryInterface using Eloquent.

**Key methods:**

- `assignToUser()` → `UserModel::find($userId)->roles()->attach($roleId)`
- `revokeFromUser()` → `UserModel::find($userId)->roles()->detach($roleId)`
- `getUserRoles()` → `UserModel::find($userId)->roles()->get()`

### Reflector

Minimal reflector needed — Role entity is simple. Convert between RoleModel and Role domain entity.

---

## Service Provider Registration

**Class:** `Modules\Role\Infrastructure\Providers\RoleServiceProvider`

**Register method:** Binds RoleRepositoryInterface to EloquentRoleRepository

**Boot method:** Loads migrations, loads routes, registers event listeners (for auto-assigning INDIVIDUAL role on UserRegistered)

**Position in bootstrap/providers.php:** After UserServiceProvider, before Event modules.

---

## Testing Strategy

### Unit Tests

- Role domain entity creation and validation
- RoleLevel enum hierarchy (can user with level X assign role level Y?)
- Role assignment business rules

### Feature Tests

- Assign role to user → pivot record created
- Revoke role from user → pivot record removed
- Cannot assign non-existent role → 404
- Cannot assign global role without system_controller authorization → 403
- UserRegistered event triggers INDIVIDUAL role assignment

### Integration Tests

- User + Role: User model has roles() relationship
- Authorization middleware: `role:general_manager` blocks individual users

---

## Security and Validation Rules

### Authorization Rules

| Action                                                                      | Required Role                            |
| --------------------------------------------------------------------------- | ---------------------------------------- |
| Assign global role (system_controller, general_manager, operations_manager) | system_controller                        |
| Revoke global role                                                          | system_controller                        |
| Assign/revoke event-scoped role                                             | Handled by EventRoleAssignment module    |
| List all roles                                                              | system_controller, general_manager       |
| View user roles                                                             | Self, system_controller, general_manager |

### Validation Rules

**AssignRoleToUser:**

- `user_id`: required, exists:users, uuid
- `role_slug`: required, exists:roles, slug
- `assigned_by`: required, exists:users, uuid (must be system_controller)

**Business Rules:**

- Cannot assign role to soft-deleted user
- Cannot assign role to inactive user
- Cannot assign system_controller role to anyone (only seeded)
- One user can have multiple global roles

---

## Events Emitted

| Event        | When               | Payload                       | Listeners                        |
| ------------ | ------------------ | ----------------------------- | -------------------------------- |
| RoleAssigned | After pivot insert | user_id, role_id, assigned_by | Log audit, clear user role cache |
| RoleRevoked  | After pivot delete | user_id, role_id, revoked_by  | Log audit, clear user role cache |

**Listener:** `AssignDefaultRole` listens to `UserRegistered` event from User module and assigns INDIVIDUAL role.

---

## Error Handling

| Code     | HTTP | Message                              | When                               |
| -------- | ---- | ------------------------------------ | ---------------------------------- |
| ROLE_001 | 404  | Role not found                       | Invalid role slug                  |
| ROLE_002 | 403  | Cannot assign system_controller role | Attempt to assign god mode         |
| ROLE_003 | 409  | Role already assigned to user        | Duplicate assignment               |
| ROLE_004 | 404  | Role not assigned to user            | Revoke on non-existent assignment  |
| ROLE_005 | 403  | Insufficient permission              | Non-admin attempts role assignment |

---

## Performance Considerations

- **Indexes:** `roles.slug` (unique), `role_user.user_id`, `role_user.role_id`
- **Caching:** User roles cached for 15 minutes, cleared on assign/revoke
- **Eager loading:** `UserModel::with('roles')` when displaying users with their roles
- **Batch operations:** Use `sync()` for bulk role updates (avoid N+1)

---

## Dependencies

### Required From Other Modules

| Module | What                      | Why                           |
| ------ | ------------------------- | ----------------------------- |
| User   | `users` table             | role_user.user_id foreign key |
| User   | `UserRepositoryInterface` | Fetch users for assignment    |
| User   | `UserRegistered` event    | Auto-assign default role      |

### Provided To Other Modules

| Recipient           | What                 | Purpose                                  |
| ------------------- | -------------------- | ---------------------------------------- |
| IAM module          | Authorization checks | `$user->hasRole('general_manager')`      |
| EventRoleAssignment | Role definitions     | Reference for event-scoped roles         |
| Any module          | Role middleware      | `->middleware('role:system_controller')` |

### No External Package Dependencies

Uses only Laravel core.

---

## Next Steps After Building Role Module

### Pre-Flight Checklist

- [ ] roles table migrated
- [ ] role_user pivot table migrated
- [ ] RoleSeeder executed (9 roles seeded)
- [ ] RoleServiceProvider registered after UserServiceProvider
- [ ] User model has `belongsToMany` roles relationship
- [ ] UserRegistered event listener assigns INDIVIDUAL role
- [ ] Authorization middleware works with role checks

### Immediate Next Module: Event

**Why Event module next?**

- Events are the atomic unit of the system
- No other module makes sense without events
- EventStaffingPosition, EventParticipation, EventContract all depend on events

**Build Order after Role:**

```
User (complete) → Role (complete) → Event (next) → EventStaffingPosition → EventParticipation → EventContract → rest
```

### Integration Point to Test

After Event module is built, test:

1. Create event with created_by = system_controller user
2. User with general_manager role can view all events
3. User with individual role cannot view events (no permission)

### Commands to Run

```bash
# Verify Role module
php artisan migrate:status | grep role
php artisan tinker --execute="App\Models\User::first()->roles()->get()"

# Create Event module
php artisan module:make Event

# Register EventServiceProvider after RoleServiceProvider
```

### Success Criteria

- [ ] Global roles restrict access correctly
- [ ] INDIVIDUAL role auto-assigned on registration
- [ ] system_controller can assign/revoke global roles
- [ ] Authorization gates work in controllers
