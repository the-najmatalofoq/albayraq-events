# EventStaffingGroup Module

## Module Purpose

The EventStaffingGroup module manages team/group assignments within an event. Groups organize workers into functional teams (e.g., "Gate A Security", "VIP Section", "Parking Team"). Each group has a name, color code for visual identification, and a lock flag to prevent changes after assignments are made. Groups are optional — workers can be assigned directly to positions without a group. This module enables team-based task assignments, attendance tracking, and communication.

---

## Table Schema

### `event_staffing_groups`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| event_id | uuid | FOREIGN KEY → events.id, CASCADE DELETE | Parent event |
| name | json | NOT NULL | Group name in Arabic/English `{"ar": "فريق أ", "en": "Team A"}` |
| color | string(7) | NOT NULL | Hex color code (e.g., #FF5733) for UI |
| is_locked | boolean | DEFAULT: false | Prevents changes to group assignments |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Validation Rules:**
- `color` must be valid hex format (#RRGGBB)
- `is_locked` can only be set to true by project_manager

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_103500_create_event_staffing_groups_table.php` | Wave 3 | #16 | events |

**Position:** Wave 3 — after event_staffing_positions, before event_role_assignments.

---

## Relations

### Foreign Keys
- `event_staffing_groups.event_id` → `events.id` (CASCADE DELETE)

### Tables That Reference Groups
| Table | Foreign Key | Module |
|-------|-------------|--------|
| event_participations | group_id | EventParticipation |
| event_tasks | group_id | EventTask |
| event_announcements | target_id (when target_type = group) | EventAnnouncement |

### Eloquent Relationships
```php
// GroupModel
public function event(): BelongsTo
public function participations(): HasMany  // EventParticipation
public function tasks(): HasMany  // EventTask
```

---

## Execution Order

**Build Sequence Position:** Wave 3, #16 — after positions, before role assignments.

```
Wave 3:
  #13: event_staffing_positions
  #14: wages
  #15: quizzes
  #16: event_staffing_groups ← YOU ARE HERE
  #17: event_role_assignments
```

**Service Provider Registration:** After EventStaffingPosition, before EventRoleAssignment.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| Event | `events` table | event_id foreign key |

### What Group Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| EventParticipation | group_id | Assign worker to group |
| EventTask | group_id | Assign task to entire group |
| EventAnnouncement | group_id | Send announcement to group |
| Reporting | Group data | Group-based analytics |

---

## Domain Entities

### Aggregate Root: `Group`

**Identity:** GroupId (UUID)

**Core Attributes:**
- **EventId:** Reference to event
- **Name:** TranslatableText — Arabic/English group name
- **Color:** HexColor value object (e.g., #FF5733)
- **IsLocked:** Boolean — when true, participations cannot be reassigned

**Business Rules:**
- Group name must be unique within an event
- Cannot lock a group with zero members (optional rule)
- Cannot modify group name or color if is_locked = true
- Cannot delete group with active participations

### Value Objects
- **GroupId:** UUID wrapper
- **HexColor:** Validates #RRGGBB format (case-insensitive)

### Repository Interface: `GroupRepositoryInterface`
- `save(Group $group): void`
- `findById(GroupId $id): ?Group`
- `findByEvent(EventId $eventId): array`
- `findLockedByEvent(EventId $eventId): array`
- `delete(GroupId $id): void`
- `hasActiveParticipations(GroupId $id): bool`

### Domain Events
- `GroupCreated` — When group is created
- `GroupLocked` — When is_locked becomes true
- `GroupUnlocked` — When is_locked becomes false (admin only)
- `GroupDeleted` — When group is removed

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `CreateGroup` | event_id, name, color, created_by | Creates unlocked group |
| `UpdateGroup` | group_id, name, color | Updates fields (fails if locked) |
| `LockGroup` | group_id, locked_by | Sets is_locked = true |
| `UnlockGroup` | group_id, unlocked_by | Sets is_locked = false (admin only) |
| `DeleteGroup` | group_id | Soft delete (fails if participations exist) |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetGroup` | group_id | Full group data with member count |
| `ListGroupsByEvent` | event_id, include_locked | Paginated groups |
| `GetGroupMembers` | group_id | List of participations in group |

---

## API Endpoints

Base path: `/api/v1/events/{event_id}/groups`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/` | CreateGroupAction | Required | project_manager, general_manager, system_controller |
| GET | `/` | ListGroupsAction | Required | project_manager, area_manager, site_manager |
| GET | `/{id}` | GetGroupAction | Required | As above |
| PUT | `/{id}` | UpdateGroupAction | Required | project_manager (if not locked) |
| POST | `/{id}/lock` | LockGroupAction | Required | project_manager |
| POST | `/{id}/unlock` | UnlockGroupAction | Required | system_controller only |
| DELETE | `/{id}` | DeleteGroupAction | Required | project_manager, system_controller |

### Request/Response Examples

**POST /events/{event_id}/groups**
Request:
```json
{
    "name": {"ar": "الفريق الأمني", "en": "Security Team"},
    "color": "#FF0000"
}
```
Response (201):
```json
{
    "id": "group_uuid",
    "name": {"ar": "الفريق الأمني", "en": "Security Team"},
    "color": "#FF0000",
    "is_locked": false,
    "member_count": 0
}
```

**GET /events/{event_id}/groups/{id}**
Response:
```json
{
    "id": "group_uuid",
    "event_id": "event_uuid",
    "name": {"ar": "الفريق الأمني", "en": "Security Team"},
    "color": "#FF0000",
    "is_locked": false,
    "member_count": 5,
    "members": [
        {"participation_id": "p1", "user": {"name": "Ahmed"}},
        {"participation_id": "p2", "user": {"name": "Sara"}}
    ],
    "created_at": "2026-03-31T10:00:00Z"
}
```

---

## Presenters API Response Format

### GroupPresenter
- id, name (ar/en), color, is_locked
- member_count (calculated from participations)
- created_at, updated_at

### GroupSummaryPresenter (for list views)
- id, name, color, is_locked, member_count (no timestamps)

---

## Seeder Data

### GroupSeeder
Creates sample groups for development events:

| Event | Group Name (ar/en) | Color | Members |
|-------|-------------------|-------|---------|
| Tech Conference | الأمن / Security | #FF0000 | 5 |
| Tech Conference | التذاكر / Tickets | #00FF00 | 3 |
| Tech Conference | الضيافة / Hospitality | #0000FF | 2 |
| Marketing Expo | التنسيق / Coordination | #FFA500 | 4 |
| Marketing Expo | التحميل / Loading | #800080 | 6 |

**Dependencies:** Requires events to exist.

**Run order:** After EventSeeder, before ParticipationSeeder.

---

## Infrastructure Implementation

### Eloquent Model: GroupModel
- Table: `event_staffing_groups`
- Casts: `name` → array, `is_locked` → boolean
- Relationships: `event()`, `participations()`, `tasks()`

### EloquentGroupRepository
Implements GroupRepositoryInterface.

**Key methods:**
- `save()` → creates/updates group
- `findByEvent()` → ordered by name
- `hasActiveParticipations()` → checks participations.count() > 0

### Reflector: GroupReflector
Converts between GroupModel and Group domain entity:
- Model → Domain: reconstructs with EventId, HexColor value object
- Domain → Model: maps attributes

---

## Service Provider Registration

**Class:** `Modules\EventStaffingGroup\Infrastructure\Providers\EventStaffingGroupServiceProvider`

**Register method:** Binds GroupRepositoryInterface to EloquentGroupRepository

**Boot method:** Loads migrations, loads routes

**Position:** After EventStaffingPosition, before EventRoleAssignment.

---

## Testing Strategy

### Unit Tests
- Group creation with valid/invalid hex color
- Lock/unlock behavior
- Cannot update locked group
- Cannot delete group with members

### Feature Tests
- Create group → 201
- Update locked group → 422
- Lock group → is_locked = true
- Delete group with participations → 409
- Non-project_manager cannot lock → 403

### Integration Tests
- Group + Participation: assign worker to group
- Group + Task: assign task to group
- Group uniqueness per event

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Create/update group | project_manager (own event) |
| Lock group | project_manager |
| Unlock group | system_controller only |
| Delete group | project_manager, system_controller |
| View groups | project_manager, area_manager, site_manager |

### Validation Rules

**CreateGroup/UpdateGroup:**
- `name.ar`: required, string, max:255
- `name.en`: required, string, max:255
- `color`: required, regex:/^#[0-9A-Fa-f]{6}$/

**Business Rules:**
- Group name must be unique per event
- Cannot delete group with active participations (status = active)
- Locked groups cannot have participations reassigned (enforced by Participation module)

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| GroupCreated | After save | group_id, event_id, name | None |
| GroupLocked | is_locked → true | group_id, locked_by | Notify group members |
| GroupDeleted | Before delete | group_id, event_id | Remove group_id from participations (set null) |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| GRP_001 | 404 | Group not found | Invalid group ID |
| GRP_002 | 422 | Group name already exists in this event | Duplicate name |
| GRP_003 | 422 | Invalid hex color format | Color not #RRGGBB |
| GRP_004 | 409 | Cannot modify locked group | Update attempt when is_locked = true |
| GRP_005 | 409 | Cannot delete group with active members | Delete with participations |
| GRP_006 | 403 | Only system_controller can unlock groups | Unlock by non-admin |

---

## Performance Considerations

- **Indexes:** `event_id`, `is_locked`, `name` (for uniqueness)
- **Composite index:** `(event_id, is_locked)` for locked group queries
- **Member count:** Use `withCount('participations')` instead of loading all members
- **Color caching:** Group colors cached for event dashboard

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| Event | `events` table | event_id foreign key |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| EventParticipation | group_id | Assign worker to group |
| EventTask | group_id | Assign task to group |
| EventAnnouncement | group_id | Target announcements |

---

## Next Steps After Building EventStaffingGroup Module

### Pre-Flight Checklist
- [ ] event_staffing_groups table migrated
- [ ] GroupSeeder executed
- [ ] GroupServiceProvider registered
- [ ] Create group via API returns 201
- [ ] Lock/unlock works
- [ ] Cannot delete group with members

### Immediate Next Module: EventRoleAssignment

**Why EventRoleAssignment next?**
- Assigns event-scoped roles (project_manager, area_manager, site_manager, supervisor)
- Independent from groups but similar event-scoped concept
- Required for authorization within events

### Build Order
```
EventStaffingPosition → EventStaffingGroup → EventRoleAssignment → EventRoleCapability → EventPositionApplication
```

### Commands to Run
```bash
php artisan migrate:status | grep event_staffing_groups
php artisan module:make EventRoleAssignment
```

### Success Criteria
- [ ] Groups created under events
- [ ] Colors displayed correctly in UI
- [ ] Lock flag prevents changes
- [ ] Member count accurate
- [ ] Groups usable by Participation, Task, Announcement modules

---

**EventStaffingGroup Module Specification Complete.**
