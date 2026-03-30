# Event Module

## Module Purpose

The Event module manages the core business entity — the **Event (الفعالية)**. Every operation in the system (contracts, attendance, evaluations, tasks, reports, financials) is scoped to an event. Events contain geolocation data (latitude, longitude, geofence radius), date ranges, daily working hours, and employment terms. Status flow: draft → published → active → pending_closure → closed.

This is an aggregate root module. No other module can exist without events.

---

## Table Schema

### `events`

| Column           | Type          | Constraints                      | Description                                                                     |
| ---------------- | ------------- | -------------------------------- | ------------------------------------------------------------------------------- |
| id               | uuid          | PRIMARY KEY                      | Auto-generated UUID                                                             |
| name             | json          | NOT NULL                         | Event name in Arabic/English `{"ar": "مؤتمر التقنية", "en": "Tech Conference"}` |
| description      | json          | NULLABLE                         | Detailed description in Arabic/English                                          |
| latitude         | decimal(10,7) | NOT NULL                         | GPS latitude for geofencing                                                     |
| longitude        | decimal(10,7) | NOT NULL                         | GPS longitude for geofencing                                                    |
| geofence_radius  | integer       | NOT NULL                         | Radius in meters (100-5000)                                                     |
| address          | json          | NULLABLE                         | Physical address in Arabic/English                                              |
| start_date       | date          | NOT NULL                         | Event start date (YYYY-MM-DD)                                                   |
| end_date         | date          | NOT NULL                         | Event end date (must be >= start_date)                                          |
| daily_start_time | time          | NOT NULL                         | Daily work start time (HH:MM:SS)                                                |
| daily_end_time   | time          | NOT NULL                         | Daily work end time (HH:MM:SS)                                                  |
| employment_terms | json          | NULLABLE                         | Contract terms, wage policies, rules                                            |
| status           | string        | DEFAULT: 'draft'                 | draft, published, active, pending_closure, closed                               |
| created_by       | uuid          | FOREIGN KEY → users.id, RESTRICT | User who created the event                                                      |
| created_at       | timestamp     | NOT NULL                         |                                                                                 |
| updated_at       | timestamp     | NOT NULL                         |                                                                                 |
| deleted_at       | timestamp     | NULLABLE                         | Soft delete                                                                     |

**Status Flow:**

```

draft → published → active → pending_closure → closed
↑ ↓ ↓ ↓
└─────────┴───────────┴──────────────┘ (cannot go back)

```

**Validation Rules:**

- `end_date` must be >= `start_date`
- `geofence_radius` between 100 and 5000 meters
- `status` transitions follow defined flow (no skipping)
- Cannot delete event with active participations (RESTRICT on created_by)

---

## Migration Details

| Migration File                              | Wave   | Order | Dependencies          |
| ------------------------------------------- | ------ | ----- | --------------------- |
| `2026_03_25_102500_create_events_table.php` | Wave 2 | #11   | users (created_by FK) |

**Position:** Wave 2 — after User module's employee_profiles, contact_phones, bank_details.

**Why Wave 2?** Events need users.created_by foreign key, but don't need any other modules.

---

## Relations

### Foreign Keys

- `events.created_by` → `users.id` (RESTRICT — cannot delete user who created events)

### Tables That Reference Events (External)

| Table                       | Foreign Key            | Module                   |
| --------------------------- | ---------------------- | ------------------------ |
| work_schedules              | event_id               | Shared                   |
| event_staffing_positions    | event_id               | EventStaffingPosition    |
| quizzes                     | event_id               | Quiz                     |
| event_staffing_groups       | event_id               | EventStaffingGroup       |
| event_role_assignments      | event_id               | EventRoleAssignment      |
| event_role_capabilities     | event_id               | EventRoleCapability      |
| event_position_applications | position_id (indirect) | EventPositionApplication |
| event_participations        | event_id               | EventParticipation       |
| attendance_barcodes         | event_id               | AttendanceBarcode        |
| discounts                   | event_id               | Discount                 |
| event_tasks                 | event_id               | EventTask                |
| event_operational_reports   | event_id               | EventOperationalReport   |
| event_asset_custodies       | event_id               | EventAssetCustody        |
| event_expenses              | event_id               | EventExpense             |
| event_announcements         | event_id               | EventAnnouncement        |

### Eloquent Relationships (in EventModel)

```php
public function createdBy(): BelongsTo  // User model
public function workSchedules(): HasMany
public function staffingPositions(): HasMany
public function quizzes(): HasMany
public function groups(): HasMany
public function participations(): HasMany
public function tasks(): HasMany
public function reports(): HasMany
public function announcements(): HasMany
```

---

## Execution Order

**Build Sequence Position:** Wave 2, #11 — after User module, before any event-dependent modules.

```
Wave 1: users table (User module) ← MUST EXIST FIRST
Wave 2:
  #8: employee_profiles
  #9: contact_phones
  #10: bank_details
  #11: events ← YOU ARE HERE
  #12: work_schedules (Shared module)
```

**Service Provider Registration:** After UserServiceProvider and RoleServiceProvider, before EventStaffingPosition.

```php
return [
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,
    Modules\Role\Infrastructure\Providers\RoleServiceProvider::class,
    Modules\Event\Infrastructure\Providers\EventServiceProvider::class,  // HERE
    // ... other modules
];
```

---

## What's Needed From Others

### From User Module (Required)

| Need                      | Purpose                             |
| ------------------------- | ----------------------------------- |
| `users` table             | created_by foreign key              |
| `User` domain entity      | Track who created the event         |
| `UserRepositoryInterface` | Fetch creator when displaying event |

### From Other Modules (None)

Event module depends only on User module.

### What Event Module Provides to Others

| Provides                   | To Whom                               | Purpose                    |
| -------------------------- | ------------------------------------- | -------------------------- |
| `events` table             | 15+ modules                           | Foreign key target         |
| `EventRepositoryInterface` | All event-dependent modules           | Fetch events               |
| `Event` domain entity      | Participation, Contract, Task modules | Core aggregate             |
| `EventStatus` enum         | Workflow modules                      | Status transitions         |
| `EventClosed` event        | Certificate, Badge modules            | Trigger post-closure tasks |

---

## Domain Entities

### Aggregate Root: `Event`

**Identity:** EventId (UUID)

**Core Attributes:**

- **Name:** TranslatableText — Arabic/English event name
- **Description:** TranslatableText — optional detailed description
- **Status:** EventStatus enum (draft, published, active, pending_closure, closed)
- **Date Range:** start_date, end_date (both Date objects)
- **Daily Hours:** daily_start_time, daily_end_time (both Time objects)

**Geolocation:**

- **Latitude:** float (-90 to 90)
- **Longitude:** float (-180 to 180)
- **Geofence Radius:** integer meters (100-5000)
- **Address:** TranslatableText — optional physical address

**Employment Terms:** JSON blob — free-form contract terms, wage policies, rules

**Audit:** created_by (UserId), created_at, updated_at, deleted_at

### Value Objects

- **EventId:** UUID wrapper
- **GeoCoordinates:** Contains latitude, longitude, geofence_radius — validates ranges
- **EventDateRange:** Contains start_date, end_date — validates end >= start
- **DailyWorkHours:** Contains daily_start_time, daily_end_time — validates start < end
- **EventStatus:** Enum with allowed transitions

### Enums

**EventStatusEnum:**

- DRAFT — initial state, editable
- PUBLISHED — visible for applications, not yet active
- ACTIVE — ongoing, attendance tracking enabled
- PENDING_CLOSURE — all operations done, awaiting closure gates
- CLOSED — final state, certificates generated, read-only

**Status Transition Rules:**

- DRAFT → PUBLISHED (publish action)
- PUBLISHED → ACTIVE (activate action, on start_date)
- ACTIVE → PENDING_CLOSURE (request closure action)
- PENDING_CLOSURE → CLOSED (close action, after gates pass)
- No reverse transitions allowed
- Cannot skip statuses (e.g., DRAFT → ACTIVE invalid)

### Repository Interface: `EventRepositoryInterface`

- `save(Event $event): void`
- `findById(EventId $id): ?Event`
- `findByStatus(EventStatus $status): array`
- `findActive(): array` (status = ACTIVE)
- `findUpcoming(): array` (start_date > today)
- `delete(EventId $id): void` (soft delete)
- `findByCreator(UserId $creatorId): array`

### Domain Events

- `EventCreated` — When event is created (payload: event_id, created_by, occurred_at)
- `EventPublished` — When status changes to PUBLISHED
- `EventActivated` — When status changes to ACTIVE
- `EventClosureRequested` — When status changes to PENDING_CLOSURE
- `EventClosed` — When status changes to CLOSED (triggers certificate generation)

---

## CQRS Commands

### Commands (Write)

| Command               | Input                                                                                                                                                  | Behavior                                                                     |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ | ---------------------------------------------------------------------------- |
| `CreateEvent`         | name, description, latitude, longitude, geofence_radius, address, start_date, end_date, daily_start_time, daily_end_time, employment_terms, created_by | Creates event with DRAFT status                                              |
| `UpdateEvent`         | event_id, any updatable fields                                                                                                                         | Updates only provided fields, validates status (cannot update CLOSED events) |
| `PublishEvent`        | event_id, published_by                                                                                                                                 | Changes status DRAFT → PUBLISHED                                             |
| `ActivateEvent`       | event_id, activated_by                                                                                                                                 | Changes status PUBLISHED → ACTIVE (typically automated on start_date)        |
| `RequestEventClosure` | event_id, requested_by                                                                                                                                 | Changes status ACTIVE → PENDING_CLOSURE                                      |
| `CloseEvent`          | event_id, closed_by                                                                                                                                    | Changes status PENDING_CLOSURE → CLOSED (checks 3 closure gates)             |
| `DeleteEvent`         | event_id, deleted_by                                                                                                                                   | Soft delete (only if no participations)                                      |

### Queries (Read)

| Query            | Input                                             | Output                                 |
| ---------------- | ------------------------------------------------- | -------------------------------------- |
| `GetEvent`       | event_id                                          | Full event data                        |
| `ListEvents`     | filters (status, date_range, creator), pagination | Paginated event list                   |
| `GetEventStatus` | event_id                                          | Current status and allowed transitions |

---

## API Endpoints

Base path: `/api/v1/events`

| Method | URI                     | Action               | Auth     | Roles Allowed                                                                             |
| ------ | ----------------------- | -------------------- | -------- | ----------------------------------------------------------------------------------------- |
| POST   | `/`                     | CreateEventAction    | Required | system_controller, general_manager, operations_manager                                    |
| GET    | `/`                     | ListEventsAction     | Required | system_controller, general_manager, operations_manager, project_manager (own events only) |
| GET    | `/{id}`                 | GetEventAction       | Required | As above + area_manager, site_manager (scoped events)                                     |
| PUT    | `/{id}`                 | UpdateEventAction    | Required | system_controller, general_manager, project_manager (own events)                          |
| POST   | `/{id}/publish`         | PublishEventAction   | Required | system_controller, general_manager, project_manager (own events)                          |
| POST   | `/{id}/activate`        | ActivateEventAction  | Required | system_controller, general_manager                                                        |
| POST   | `/{id}/request-closure` | RequestClosureAction | Required | system_controller, general_manager, project_manager                                       |
| POST   | `/{id}/close`           | CloseEventAction     | Required | system_controller, general_manager                                                        |
| DELETE | `/{id}`                 | DeleteEventAction    | Required | system_controller only                                                                    |

### Request/Response Examples

**POST /events**
Request:

```json
{
    "name": { "ar": "مؤتمر التقنية", "en": "Tech Conference" },
    "description": { "ar": "وصف", "en": "Description" },
    "latitude": 24.7136,
    "longitude": 46.6753,
    "geofence_radius": 500,
    "address": { "ar": "الرياض", "en": "Riyadh" },
    "start_date": "2026-06-01",
    "end_date": "2026-06-03",
    "daily_start_time": "08:00:00",
    "daily_end_time": "17:00:00",
    "employment_terms": { "wage": "hourly", "overtime_rate": 1.5 }
}
```

Response (201):

```json
{
    "id": "uuid",
    "status": "draft",
    "message": "Event created successfully"
}
```

**GET /events/{id}**
Response:

```json
{
    "id": "uuid",
    "name": { "ar": "مؤتمر التقنية", "en": "Tech Conference" },
    "description": { "ar": "وصف", "en": "Description" },
    "location": {
        "latitude": 24.7136,
        "longitude": 46.6753,
        "geofence_radius": 500,
        "address": { "ar": "الرياض", "en": "Riyadh" }
    },
    "date_range": {
        "start_date": "2026-06-01",
        "end_date": "2026-06-03"
    },
    "daily_hours": {
        "start": "08:00:00",
        "end": "17:00:00"
    },
    "status": "active",
    "created_by": {
        "id": "user_uuid",
        "name": { "ar": "مدير النظام", "en": "System Controller" }
    },
    "created_at": "2026-03-31T10:00:00Z"
}
```

---

## Presenters API Response Format

### EventPresenter

Transforms Event domain object to API response:

- Flattens location fields into `location` object
- Groups date fields into `date_range` object
- Groups time fields into `daily_hours` object
- Includes creator user summary (not full user)

### EventSummaryPresenter (for list views)

Reduced response: only id, name, date_range, status, location coordinates (no address, no description, no employment_terms)

---

## Seeder Data

### EventSeeder

Creates sample events for development:

| Name (ar/en)                    | Start Date | End Date   | Location | Status    |
| ------------------------------- | ---------- | ---------- | -------- | --------- |
| مؤتمر التقنية / Tech Conference | 2026-06-01 | 2026-06-03 | Riyadh   | published |
| معرض التسويق / Marketing Expo   | 2026-07-15 | 2026-07-17 | Jeddah   | draft     |
| ورشة العمل / Workshop           | 2026-08-10 | 2026-08-10 | Dammam   | active    |

**Dependencies:** Requires at least one system_controller user to set as created_by.

**Run order:** After UserSeeder, before any event-dependent seeders.

---

## Infrastructure Implementation

### Eloquent Model: EventModel

- Table: `events`
- Casts: `name` → array, `description` → array, `address` → array, `employment_terms` → array, `status` → string, `deleted_at` → datetime
- Relationships: `createdBy()` → BelongsTo (UserModel)
- Soft deletes: uses SoftDeletes trait

### EloquentEventRepository

Implements EventRepositoryInterface.

**Key methods:**

- `save()` → updates or creates EventModel, handles JSON casts
- `findById()` → eager loads createdBy relationship
- `findByStatus()` → scope query by status
- `findActive()` → where('status', 'active')->whereNull('deleted_at')
- `delete()` → soft delete (sets deleted_at)

### Reflector: EventReflector

Converts between EventModel and Event domain entity:

- Model → Domain: reconstructs Event aggregate with Value Objects
- Domain → Model: maps attributes for persistence

---

## Service Provider Registration

**Class:** `Modules\Event\Infrastructure\Providers\EventServiceProvider`

**Register method:** Binds EventRepositoryInterface to EloquentEventRepository

**Boot method:** Loads migrations, loads routes, registers event listeners (none in MVP)

**Position in bootstrap/providers.php:** After User and Role, before EventStaffingPosition.

---

## Testing Strategy

### Unit Tests

- Event domain entity creation and validation
- Status transition rules (cannot skip, cannot reverse)
- GeoCoordinates validation (lat/long ranges)
- DateRange validation (end >= start)

### Feature Tests

- Create event → status = draft
- Publish event → status changes to published
- Cannot publish closed event → 422
- Cannot update closed event → 422
- Delete event with participations → 409 (RESTRICT)
- Non-admin cannot create event → 403

### Integration Tests

- Event + User: created_by relationship works
- Event + EventParticipation: participations belong to event
- Closure gates: cannot close event with missing evaluations

---

## Security and Validation Rules

### Authorization Rules

| Action           | Required Role                                          |
| ---------------- | ------------------------------------------------------ |
| Create event     | system_controller, general_manager, operations_manager |
| Update any event | system_controller, general_manager                     |
| Update own event | project_manager (assigned via EventRoleAssignment)     |
| Publish event    | system_controller, general_manager, project_manager    |
| Activate event   | system_controller, general_manager                     |
| Close event      | system_controller, general_manager                     |
| Delete event     | system_controller only                                 |

### Validation Rules

**CreateEvent:**

- `name.ar`: required, string, max:255
- `name.en`: required, string, max:255
- `latitude`: required, numeric, between:-90,90
- `longitude`: required, numeric, between:-180,180
- `geofence_radius`: required, integer, min:100, max:5000
- `start_date`: required, date, after_or_equal:today
- `end_date`: required, date, after_or_equal:start_date
- `daily_start_time`: required, date_format:H:i:s
- `daily_end_time`: required, date_format:H:i:s, after:daily_start_time

**UpdateEvent:**

- Cannot update closed events
- Cannot change dates if participations exist
- Cannot reduce geofence_radius if active check-ins exist

---

## Events Emitted

| Event                 | When                     | Payload                | Listeners                     |
| --------------------- | ------------------------ | ---------------------- | ----------------------------- |
| EventCreated          | After save               | event_id, created_by   | Log audit                     |
| EventPublished        | Status → PUBLISHED       | event_id, published_by | Notify staffing managers      |
| EventActivated        | Status → ACTIVE          | event_id, activated_by | Enable attendance system      |
| EventClosureRequested | Status → PENDING_CLOSURE | event_id, requested_by | Trigger closure gate checks   |
| EventClosed           | Status → CLOSED          | event_id, closed_by    | Generate certificates, badges |

---

## Error Handling

| Code      | HTTP | Message                                  | When                                        |
| --------- | ---- | ---------------------------------------- | ------------------------------------------- |
| EVENT_001 | 404  | Event not found                          | Invalid event ID                            |
| EVENT_002 | 422  | Invalid status transition                | Cannot transition from DRAFT to CLOSED      |
| EVENT_003 | 409  | Cannot update closed event               | Update attempt on CLOSED status             |
| EVENT_004 | 409  | Cannot delete event with participations  | Delete attempt with existing participations |
| EVENT_005 | 422  | End date must be after start date        | Invalid date range                          |
| EVENT_006 | 422  | Geofence radius out of range             | <100 or >5000 meters                        |
| EVENT_007 | 403  | Cannot close event: closure gates failed | Missing evaluations or reports              |
| EVENT_008 | 403  | Insufficient permission for event action | User not authorized for this event          |

---

## Performance Considerations

- **Indexes:** `events.status`, `events.start_date`, `events.end_date`, `events.created_by`, `events.deleted_at`
- **Composite index:** `(status, start_date)` for active/upcoming event queries
- **Caching:** Event list cached for 5 minutes, cleared on any event change
- **Soft deletes:** Always filter `whereNull('deleted_at')` in queries
- **Geofence queries:** Use MySQL spatial indexes if high-volume check-ins (optional)

---

## Dependencies

### Required From Other Modules

| Module | What                      | Why                         |
| ------ | ------------------------- | --------------------------- |
| User   | `users` table             | created_by foreign key      |
| User   | `UserRepositoryInterface` | Fetch creator for presenter |

### Provided To Other Modules

| Recipient               | What                             | Purpose                               |
| ----------------------- | -------------------------------- | ------------------------------------- |
| EventStaffingPosition   | events table                     | Positions belong to event             |
| EventParticipation      | events table                     | Participations belong to event        |
| EventContract           | events table (via participation) | Contracts scoped to event             |
| EventAttendance         | events table                     | Attendance records scoped to event    |
| EventTask               | events table                     | Tasks scoped to event                 |
| EventAnnouncement       | events table                     | Announcements scoped to event         |
| All operational modules | Event status                     | Gate operations based on event status |

### No External Package Dependencies

Uses only Laravel core + User module.

---

## Next Steps After Building Event Module

### Pre-Flight Checklist

- [ ] events table migrated
- [ ] EventSeeder executed (at least 1 event)
- [ ] EventServiceProvider registered after RoleServiceProvider
- [ ] Create event via API returns 201 with DRAFT status
- [ ] Status transitions work (draft → published → active → pending_closure → closed)
- [ ] Geolocation fields validated correctly
- [ ] created_by foreign key references valid user

### Immediate Next Module: EventStaffingPosition

**Why EventStaffingPosition next?**

- Positions are the first child entity of Event
- EventPositionApplication depends on positions
- EventParticipation depends on positions
- Wages are tied to positions

**Build Order after Event:**

```
User → Role → Event → EventStaffingPosition → EventStaffingGroup → EventRoleAssignment → EventPositionApplication → EventParticipation → rest
```

### Integration Point to Test

After EventStaffingPosition module is built, test:

1. Create position under an event
2. Position has event_id foreign key to events table
3. Wage belongs to position
4. Cannot create position for non-existent event

### Commands to Run

```bash
# Verify Event module
php artisan migrate:status | grep events
php artisan tinker --execute="Modules\Event\Domain\Event::find('event-id')"

# Create EventStaffingPosition module
php artisan module:make EventStaffingPosition

# Register provider after EventServiceProvider
```

### Success Criteria

- [ ] Events can be created with all required fields
- [ ] Status workflow works correctly
- [ ] Geolocation data stored and retrievable
- [ ] Soft delete preserves event history
- [ ] Only authorized users can modify events
- [ ] Closure gates prevent premature closure

---

**Event Module Specification Complete.**
