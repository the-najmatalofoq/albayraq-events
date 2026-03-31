# EventRoleAssignment Module

## Module Purpose

The EventRoleAssignment module manages event-scoped roles. Unlike global roles (system_controller, general_manager, operations_manager) which apply system-wide, event-scoped roles only grant permissions within a specific event. These roles include: project_manager (manages one event fully), area_manager (manages areas within an event), site_manager (manages one group), supervisor (manages team, generates barcodes), admissions_admin (reviews applications), and individual (default worker role). Each assignment links a user to an event with a specific role. A user can have different roles in different events.

---

## Table Schema

### `event_role_assignments`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| event_id | uuid | FOREIGN KEY → events.id, CASCADE DELETE | Event where role applies |
| user_id | uuid | FOREIGN KEY → users.id, CASCADE DELETE | User receiving the role |
| role_id | uuid | FOREIGN KEY → roles.id, RESTRICT | Role being assigned (must be event-scoped) |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique Constraint:** `(event_id, user_id, role_id)` — prevents duplicate assignments.

**Role Scope Validation:** Only roles with `is_global = false` can be assigned here.

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_104000_create_event_role_assignments_table.php` | Wave 3 | #17 | events, users, roles |

**Position:** Wave 3 — after event_staffing_groups, before event_role_capabilities.

---

## Relations

### Foreign Keys
- `event_role_assignments.event_id` → `events.id` (CASCADE DELETE)
- `event_role_assignments.user_id` → `users.id` (CASCADE DELETE)
- `event_role_assignments.role_id` → `roles.id` (RESTRICT)

### Eloquent Relationships
```php
// EventRoleAssignmentModel
public function event(): BelongsTo
public function user(): BelongsTo
public function role(): BelongsTo
```

---

## Execution Order

**Build Sequence Position:** Wave 3, #17 — after groups, before capabilities.

```
Wave 3:
  #16: event_staffing_groups
  #17: event_role_assignments ← YOU ARE HERE
  #18: event_role_capabilities
```

**Service Provider Registration:** After EventStaffingGroup, before EventRoleCapability.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| Event | `events` table | event_id foreign key |
| User | `users` table | user_id foreign key |
| Role | `roles` table, role definitions | role_id foreign key, validate is_global = false |

### What Role Assignment Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| IAM/Authorization | User's event roles | Permission checks within event context |
| EventPositionApplication | admissions_admin role | Who can review applications |
| EventTask | project_manager, area_manager, site_manager | Who can assign tasks |
| EventAnnouncement | project_manager, area_manager | Who can send announcements |
| EventContract | project_manager | Who can approve/reject contracts |
| Attendance override | site_manager, area_manager | Who can override attendance |

---

## Domain Entities

### Aggregate Root: `EventRoleAssignment`

**Identity:** EventRoleAssignmentId (UUID)

**Core Attributes:**
- **EventId:** Reference to event
- **UserId:** Reference to user
- **RoleId:** Reference to role (must be event-scoped)

**Business Rules:**
- Cannot assign global role through this module
- One user can have multiple roles in same event
- Role assignments persist even if user is inactive (but permissions revoked)
- Cannot assign role to user already participating? (No — manager may not be participant)

### Value Objects
- **EventRoleAssignmentId:** UUID wrapper
- **EventRole:** Composite of (event_id, role_id)

### Repository Interface: `EventRoleAssignmentRepositoryInterface`
- `save(EventRoleAssignment $assignment): void`
- `findById(EventRoleAssignmentId $id): ?EventRoleAssignment`
- `findByEvent(EventId $eventId): array`
- `findByUser(UserId $userId): array`
- `findByUserAndEvent(UserId $userId, EventId $eventId): array`
- `findByRole(RoleId $roleId): array`
- `delete(EventRoleAssignmentId $id): void`
- `hasRole(UserId $userId, EventId $eventId, string $roleSlug): bool`

### Domain Events
- `EventRoleAssigned` — When role assigned to user in event
- `EventRoleRevoked` — When role removed

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `AssignEventRole` | event_id, user_id, role_id, assigned_by | Creates assignment, validates role is event-scoped |
| `RevokeEventRole` | assignment_id, revoked_by | Removes assignment |
| `RevokeAllUserEventRoles` | user_id, event_id, revoked_by | Removes all roles for user in event |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetEventRolesForUser` | user_id, event_id | Array of roles for user in event |
| `ListEventAssignments` | event_id, role_id (optional) | Paginated assignments |
| `GetUsersByEventRole` | event_id, role_slug | List of users with that role |

---

## API Endpoints

Base path: `/api/v1/events/{event_id}/roles`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/assign` | AssignEventRoleAction | Required | system_controller, general_manager, project_manager (own event) |
| DELETE | `/revoke/{assignment_id}` | RevokeEventRoleAction | Required | As above |
| GET | `/` | ListAssignmentsAction | Required | project_manager, area_manager, general_manager |
| GET | `/users/{user_id}` | GetUserEventRolesAction | Required | Self, project_manager, general_manager |

### Request/Response Examples

**POST /events/{event_id}/roles/assign**
Request:
```json
{
    "user_id": "user_uuid",
    "role_slug": "supervisor"
}
```
Response (201):
```json
{
    "id": "assignment_uuid",
    "user_id": "user_uuid",
    "user_name": {"ar": "أحمد", "en": "Ahmed"},
    "role_slug": "supervisor",
    "role_name": {"ar": "مشرف", "en": "Supervisor"},
    "message": "Role assigned successfully"
}
```

**GET /events/{event_id}/roles/users/{user_id}**
Response:
```json
{
    "user_id": "user_uuid",
    "event_id": "event_uuid",
    "roles": [
        {"slug": "supervisor", "name": {"ar": "مشرف", "en": "Supervisor"}},
        {"slug": "site_manager", "name": {"ar": "مدير موقع", "en": "Site Manager"}}
    ]
}
```

**GET /events/{event_id}/roles?role_slug=supervisor**
Response:
```json
{
    "data": [
        {
            "id": "assignment_uuid",
            "user": {"id": "u1", "name": {"ar": "أحمد", "en": "Ahmed"}},
            "role": {"slug": "supervisor", "name": {"ar": "مشرف", "en": "Supervisor"}},
            "assigned_at": "2026-03-31T10:00:00Z"
        }
    ],
    "meta": {"total": 5}
}
```

---

## Presenters API Response Format

### EventRoleAssignmentPresenter
- id, event_id, user_id, role_id
- Embedded user summary (id, name)
- Embedded role summary (slug, name, level)
- assigned_at timestamp

### UserEventRolesPresenter
- user_id
- event_id
- roles array (slug, name, level)

---

## Seeder Data

### EventRoleAssignmentSeeder
Creates sample event role assignments:

| Event | User | Role |
|-------|------|------|
| Tech Conference | System Controller | project_manager |
| Tech Conference | Employee 1 | supervisor |
| Tech Conference | Employee 2 | site_manager |
| Tech Conference | Employee 3 | individual |
| Marketing Expo | General Manager | project_manager |
| Marketing Expo | Employee 4 | area_manager |

**Dependencies:** Requires events, users, roles.

**Run order:** After RoleSeeder, before EventPositionApplicationSeeder.

---

## Infrastructure Implementation

### Eloquent Model: EventRoleAssignmentModel
- Table: `event_role_assignments`
- Casts: None (all UUIDs and timestamps)
- Relationships: `event()`, `user()`, `role()`

### EloquentEventRoleAssignmentRepository
Implements EventRoleAssignmentRepositoryInterface.

**Key methods:**
- `save()` → creates assignment, validates role.is_global = false
- `findByUserAndEvent()` → returns array of roles
- `hasRole()` → boolean check for authorization

### Reflector: EventRoleAssignmentReflector
Converts between Model and Domain entity.

---

## Service Provider Registration

**Class:** `Modules\EventRoleAssignment\Infrastructure\Providers\EventRoleAssignmentServiceProvider`

**Register method:** Binds EventRoleAssignmentRepositoryInterface to EloquentEventRoleAssignmentRepository

**Boot method:** Loads migrations, loads routes

**Position:** After EventStaffingGroup, before EventRoleCapability.

---

## Testing Strategy

### Unit Tests
- Cannot assign global role (system_controller) → exception
- Unique constraint (event+user+role)
- Role assignment retrieval

### Feature Tests
- Assign supervisor role → 201
- Assign system_controller role → 422 (invalid scope)
- Revoke role → assignment removed
- Duplicate assignment → 409
- Non-project_manager cannot assign → 403

### Integration Tests
- Role assignment affects authorization middleware
- User has correct permissions in event context

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Assign/revoke any role in event | system_controller, general_manager, project_manager (own event) |
| Assign/revoke project_manager | system_controller only |
| View assignments | project_manager, area_manager, general_manager |

### Validation Rules

**AssignEventRole:**
- `user_id`: required, exists:users, user must be active
- `event_id`: required, exists:events
- `role_slug`: required, exists:roles, role.is_global must be false

**Business Rules:**
- Cannot assign role to inactive user
- Cannot assign role to deleted user
- Cannot assign project_manager role unless system_controller

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| EventRoleAssigned | After assignment | assignment_id, user_id, event_id, role_id, assigned_by | Clear permission cache |
| EventRoleRevoked | After revocation | assignment_id, user_id, event_id, role_id, revoked_by | Clear permission cache |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| EVROLE_001 | 404 | Assignment not found | Invalid assignment ID |
| EVROLE_002 | 422 | Cannot assign global role to event | role.is_global = true |
| EVROLE_003 | 409 | Role already assigned to user in this event | Duplicate |
| EVROLE_004 | 422 | User is inactive | Cannot assign role |
| EVROLE_005 | 403 | Cannot assign project_manager role | Insufficient permission |
| EVROLE_006 | 422 | Role not event-scoped | Role missing event context |

---

## Performance Considerations

- **Indexes:** `(event_id, user_id)`, `(event_id, role_id)`, `user_id`
- **Composite unique:** `(event_id, user_id, role_id)`
- **Caching:** User's event roles cached for 15 minutes, cleared on assign/revoke
- **Eager loading:** Always load `user` and `role` when displaying assignments

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| Event | `events` table | event_id FK |
| User | `users` table | user_id FK |
| Role | `roles` table, is_global flag | Validate role scope |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| IAM/Authorization | `hasEventRole()` method | Permission checks |
| EventPositionApplication | admissions_admin role | Review access |
| EventTask | manager roles | Task assignment authority |
| EventAnnouncement | sender authorization | Announcement permissions |

---

## Next Steps After Building EventRoleAssignment Module

### Pre-Flight Checklist
- [ ] event_role_assignments table migrated
- [ ] Unique constraint working
- [ ] Cannot assign global roles
- [ ] Assign/revoke via API works
- [ ] Authorization uses event roles correctly

### Immediate Next Module: EventRoleCapability

**Why EventRoleCapability next?**
- Fine-grained permissions beyond role level
- Example: `approve_contracts`, `manage_announcements`
- Depends on event_role_assignments for context

### Build Order
```
EventStaffingGroup → EventRoleAssignment → EventRoleCapability → EventPositionApplication
```

### Commands to Run
```bash
php artisan migrate:status | grep event_role_assignments
php artisan module:make EventRoleCapability
```

### Success Criteria
- [ ] Event-scoped roles assignable
- [ ] project_manager can manage their event
- [ ] supervisor can generate barcodes
- [ ] admissions_admin can review applications
- [ ] individual role has no special permissions

---

**EventRoleAssignment Module Specification Complete.**
```
