# EventStaffingPosition Module

## Module Purpose

The EventStaffingPosition module manages job positions within an event. Each event has multiple staffing positions (e.g., "Security Guard", "Ticket Inspector", "Team Leader"). Positions define the role requirements, headcount needed, wage amount and type (daily/monthly), and whether the position is announced for applications. This module is the bridge between Event and EventParticipation — workers apply to positions, and if accepted, become participants.

Positions are event-scoped. A position cannot exist without an event.

---

## Table Schema

### `event_staffing_positions`

| Column       | Type          | Constraints                             | Description                                                                   |
| ------------ | ------------- | --------------------------------------- | ----------------------------------------------------------------------------- |
| id           | uuid          | PRIMARY KEY                             | Auto-generated UUID                                                           |
| event_id     | uuid          | FOREIGN KEY → events.id, CASCADE DELETE | Parent event                                                                  |
| title        | json          | NOT NULL                                | Position title in Arabic/English `{"ar": "حارس أمن", "en": "Security Guard"}` |
| wage_amount  | decimal(10,2) | NOT NULL                                | Base wage amount (SAR)                                                        |
| wage_type    | string        | NOT NULL                                | daily or monthly                                                              |
| headcount    | integer       | NOT NULL                                | Number of workers needed for this position                                    |
| requirements | json          | NULLABLE                                | Skills, certifications, experience needed                                     |
| is_announced | boolean       | DEFAULT: false                          | Whether position is open for applications                                     |
| created_at   | timestamp     | NOT NULL                                |                                                                               |
| updated_at   | timestamp     | NOT NULL                                |                                                                               |

**Wage Type Values:**

- `daily` — Worker paid per day worked
- `monthly` — Worker paid fixed monthly salary

**Validation Rules:**

- `wage_amount` >= 0 (can be zero for volunteers)
- `headcount` >= 1
- `requirements` structure: `{"skills": [], "certifications": [], "min_experience_years": 0}`

### `wages`

| Column      | Type          | Constraints                                               | Description                      |
| ----------- | ------------- | --------------------------------------------------------- | -------------------------------- |
| id          | uuid          | PRIMARY KEY                                               | Auto-generated UUID              |
| position_id | uuid          | FOREIGN KEY → event_staffing_positions.id, CASCADE DELETE | Parent position                  |
| wage_type   | string        | NOT NULL                                                  | daily, monthly, hourly, overtime |
| rate        | decimal(10,2) | NOT NULL                                                  | Wage rate amount                 |
| currency    | string        | DEFAULT: 'SAR'                                            | Currency code (SAR, USD, etc.)   |
| created_at  | timestamp     | NOT NULL                                                  |                                  |
| updated_at  | timestamp     | NOT NULL                                                  |                                  |

**Note:** `wages` table supports multiple wage types per position (e.g., base daily + overtime rate). The main `wage_amount` and `wage_type` on positions table is the primary/default wage.

---

## Migration Details

| Migration File                                                | Wave   | Order | Dependencies             |
| ------------------------------------------------------------- | ------ | ----- | ------------------------ |
| `2026_03_25_103000_create_event_staffing_positions_table.php` | Wave 3 | #13   | events                   |
| `2026_03_25_103050_create_wages_table.php`                    | Wave 3 | #14   | event_staffing_positions |

**Position:** Wave 3 — after events table, before event_participations.

---

## Relations

### Foreign Keys

- `event_staffing_positions.event_id` → `events.id` (CASCADE DELETE)
- `wages.position_id` → `event_staffing_positions.id` (CASCADE DELETE)

### Tables That Reference Positions (External)

| Table                       | Foreign Key | Module                   |
| --------------------------- | ----------- | ------------------------ |
| event_position_applications | position_id | EventPositionApplication |
| event_participations        | position_id | EventParticipation       |

### Eloquent Relationships (in PositionModel)

```php
public function event(): BelongsTo  // Event module
public function wages(): HasMany     // wages table
public function applications(): HasMany  // EventPositionApplication module
public function participations(): HasMany  // EventParticipation module
```

---

## Execution Order

**Build Sequence Position:** Wave 3, #13 — immediately after Event module.

```
Wave 2:
  #11: events table
  #12: work_schedules

Wave 3:
  #13: event_staffing_positions ← YOU ARE HERE
  #14: wages
  #15: quizzes
  #16: event_staffing_groups
  #17: event_role_assignments
  #18: event_role_capabilities
```

**Service Provider Registration:** After EventServiceProvider, before EventPositionApplication.

```php
return [
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,
    Modules\Role\Infrastructure\Providers\RoleServiceProvider::class,
    Modules\Event\Infrastructure\Providers\EventServiceProvider::class,
    Modules\EventStaffingPosition\Infrastructure\Providers\EventStaffingPositionServiceProvider::class, // HERE
    // ...
];
```

---

## What's Needed From Others

### From Event Module (Required)

| Need                       | Purpose                                        |
| -------------------------- | ---------------------------------------------- |
| `events` table             | event_id foreign key                           |
| `Event` domain entity      | Position belongs to event                      |
| `EventRepositoryInterface` | Validate event exists before creating position |

### From User Module (Indirect)

- Created_by tracking (optional — not in schema but may be added)

### What Position Module Provides to Others

| Provides                         | To Whom                            | Purpose                        |
| -------------------------------- | ---------------------------------- | ------------------------------ |
| `event_staffing_positions` table | EventPositionApplication           | Applications target positions  |
| `event_staffing_positions` table | EventParticipation                 | Participations have a position |
| `PositionRepositoryInterface`    | Application, Participation modules | Fetch positions                |
| `Wage` value object              | Contract module                    | Calculate worker pay           |

---

## Domain Entities

### Aggregate Root: `Position`

**Identity:** PositionId (UUID)

**Core Attributes:**

- **Title:** TranslatableText — Arabic/English position name
- **Wage Amount:** Decimal — base pay (10,2 precision)
- **Wage Type:** Enum — daily or monthly
- **Headcount:** Integer — number of workers needed
- **Requirements:** Value object — skills, certifications, experience
- **Is Announced:** Boolean — whether open for applications
- **EventId:** Reference to parent event

**Business Rules:**

- Headcount cannot be less than number of approved applications
- Cannot reduce headcount below current participant count
- Wage amount cannot be negative (zero allowed for volunteers)
- Requirements are optional but recommended for filtering

### Value Objects

- **PositionId:** UUID wrapper
- **Wage:** Contains amount, type (daily/monthly), currency — validates positive amount
- **PositionRequirements:** Contains skills array, certifications array, min_experience_years — validates structure
- **WageTypeEnum:** DAILY, MONTHLY

### Repository Interface: `PositionRepositoryInterface`

- `save(Position $position): void`
- `findById(PositionId $id): ?Position`
- `findByEvent(EventId $eventId): array`
- `findAnnounced(): array` (is_announced = true)
- `findByWageType(WageTypeEnum $type): array`
- `delete(PositionId $id): void`
- `updateHeadcount(PositionId $id, int $newHeadcount): void`

### Domain Events

- `PositionCreated` — When position is created (payload: position_id, event_id, title)
- `PositionAnnounced` — When is_announced changes to true
- `PositionUnannounced` — When is_announced changes to false
- `HeadcountChanged` — When headcount is updated (payload: old_count, new_count)
- `WageUpdated` — When wage_amount or wage_type changes

---

## CQRS Commands

### Commands (Write)

| Command              | Input                                                                        | Behavior                                             |
| -------------------- | ---------------------------------------------------------------------------- | ---------------------------------------------------- |
| `CreatePosition`     | event_id, title, wage_amount, wage_type, headcount, requirements, created_by | Creates position with is_announced = false           |
| `UpdatePosition`     | position_id, any updatable fields                                            | Updates title, wage, headcount, requirements         |
| `AnnouncePosition`   | position_id                                                                  | Sets is_announced = true, opens for applications     |
| `UnannouncePosition` | position_id                                                                  | Sets is_announced = false, closes applications       |
| `UpdateHeadcount`    | position_id, new_headcount                                                   | Validates new_count >= existing participations       |
| `DeletePosition`     | position_id                                                                  | Soft delete (only if no applications/participations) |
| `AddWage`            | position_id, wage_type, rate, currency                                       | Adds additional wage rate (overtime, etc.)           |
| `RemoveWage`         | wage_id                                                                      | Removes wage rate                                    |

### Queries (Read)

| Query                   | Input               | Output                             |
| ----------------------- | ------------------- | ---------------------------------- |
| `GetPosition`           | position_id         | Full position data with wages      |
| `ListPositionsByEvent`  | event_id, filters   | Paginated positions for an event   |
| `GetAnnouncedPositions` | event_id (optional) | Positions with is_announced = true |

---

## API Endpoints

Base path: `/api/v1/events/{event_id}/positions`

| Method | URI                | Action                   | Auth     | Roles Allowed                                                   |
| ------ | ------------------ | ------------------------ | -------- | --------------------------------------------------------------- |
| POST   | `/`                | CreatePositionAction     | Required | system_controller, general_manager, project_manager (own event) |
| GET    | `/`                | ListPositionsAction      | Required | As above + area_manager, site_manager (scoped)                  |
| GET    | `/{id}`            | GetPositionAction        | Required | As above                                                        |
| PUT    | `/{id}`            | UpdatePositionAction     | Required | system_controller, general_manager, project_manager             |
| POST   | `/{id}/announce`   | AnnouncePositionAction   | Required | system_controller, general_manager, project_manager             |
| POST   | `/{id}/unannounce` | UnannouncePositionAction | Required | As above                                                        |
| DELETE | `/{id}`            | DeletePositionAction     | Required | system_controller, general_manager                              |

### Request/Response Examples

**POST /events/{event_id}/positions**
Request:

```json
{
    "title": { "ar": "حارس أمن", "en": "Security Guard" },
    "wage_amount": 150.0,
    "wage_type": "daily",
    "headcount": 10,
    "requirements": {
        "skills": ["المراقبة", "التواصل"],
        "certifications": ["شهادة أمن"],
        "min_experience_years": 1
    }
}
```

Response (201):

```json
{
    "id": "uuid",
    "message": "Position created successfully"
}
```

**GET /events/{event_id}/positions/{id}**
Response:

```json
{
    "id": "uuid",
    "event_id": "event_uuid",
    "title": { "ar": "حارس أمن", "en": "Security Guard" },
    "wage": {
        "amount": 150.0,
        "type": "daily",
        "currency": "SAR"
    },
    "headcount": 10,
    "filled_count": 3,
    "requirements": {
        "skills": ["المراقبة", "التواصل"],
        "certifications": ["شهادة أمن"],
        "min_experience_years": 1
    },
    "is_announced": true,
    "created_at": "2026-03-31T10:00:00Z"
}
```

---

## Presenters API Response Format

### PositionPresenter

Transforms Position domain object to API response:

- Flattens wage fields into `wage` object
- Includes `filled_count` (number of approved participations for this position)
- Includes event summary (id, name)

### PositionSummaryPresenter (for list views)

Reduced response: id, title, wage_amount, wage_type, headcount, filled_count, is_announced

---

## Seeder Data

### PositionSeeder

Creates sample positions for development events:

| Event           | Title (ar/en)                 | Wage Amount | Type    | Headcount |
| --------------- | ----------------------------- | ----------- | ------- | --------- |
| Tech Conference | حارس أمن / Security Guard     | 150.00      | daily   | 10        |
| Tech Conference | مفتش تذاكر / Ticket Inspector | 120.00      | daily   | 5         |
| Tech Conference | قائد فريق / Team Leader       | 200.00      | daily   | 2         |
| Marketing Expo  | منسق / Coordinator            | 3000.00     | monthly | 3         |
| Marketing Expo  | عامل تحميل / Loader           | 100.00      | daily   | 8         |

**Dependencies:** Requires events to exist.

**Run order:** After EventSeeder, before EventPositionApplicationSeeder.

---

## Infrastructure Implementation

### Eloquent Models

**PositionModel:**

- Table: `event_staffing_positions`
- Casts: `title` → array, `requirements` → array, `wage_amount` → decimal, `is_announced` → boolean
- Relationships: `event()` → BelongsTo, `wages()` → HasMany, `applications()` → HasMany, `participations()` → HasMany

**WageModel:**

- Table: `wages`
- Casts: `rate` → decimal
- Relationships: `position()` → BelongsTo

### EloquentPositionRepository

Implements PositionRepositoryInterface.

**Key methods:**

- `save()` → updates or creates PositionModel, handles JSON casts for requirements
- `findByEvent()` → where('event_id', $eventId)
- `findAnnounced()` → where('is_announced', true)
- `updateHeadcount()` → validates new count >= existing participations count

### Reflector: PositionReflector

Converts between PositionModel and Position domain entity:

- Model → Domain: reconstructs Position with Wage value objects
- Domain → Model: maps attributes, handles requirements JSON

---

## Service Provider Registration

**Class:** `Modules\EventStaffingPosition\Infrastructure\Providers\EventStaffingPositionServiceProvider`

**Register method:** Binds PositionRepositoryInterface to EloquentPositionRepository

**Boot method:** Loads migrations, loads routes

**Position in bootstrap/providers.php:** After EventServiceProvider, before EventPositionApplication.

---

## Testing Strategy

### Unit Tests

- Position creation with valid/invalid wage amounts
- Headcount validation (cannot be less than filled positions)
- Requirements structure validation
- Wage type enum validation

### Feature Tests

- Create position under event → 201
- Create position for non-existent event → 404
- Update headcount below filled count → 422
- Announce position → is_announced = true
- Delete position with applications → 409
- Non-project_manager cannot create position → 403

### Integration Tests

- Position + Event: position belongs to event
- Position + Application: applications reference position
- Position + Participation: participations reference position

---

## Security and Validation Rules

### Authorization Rules

| Action              | Required Role                                                   |
| ------------------- | --------------------------------------------------------------- |
| Create position     | project_manager (own event), general_manager, system_controller |
| Update position     | As above                                                        |
| Announce/unannounce | As above                                                        |
| Delete position     | system_controller, general_manager                              |

### Validation Rules

**CreatePosition:**

- `title.ar`: required, string, max:255
- `title.en`: required, string, max:255
- `wage_amount`: required, numeric, min:0
- `wage_type`: required, in:daily,monthly
- `headcount`: required, integer, min:1
- `requirements`: nullable, array

**UpdatePosition:**

- Cannot reduce headcount below existing participations count
- Cannot update wage_amount if contracts already signed for this position

---

## Events Emitted

| Event             | When                | Payload                           | Listeners                           |
| ----------------- | ------------------- | --------------------------------- | ----------------------------------- |
| PositionCreated   | After save          | position_id, event_id, title      | None                                |
| PositionAnnounced | is_announced → true | position_id                       | Notify potential applicants         |
| HeadcountChanged  | Headcount updated   | position_id, old_count, new_count | Check if new_count < filled → error |

---

## Error Handling

| Code    | HTTP | Message                                           | When                             |
| ------- | ---- | ------------------------------------------------- | -------------------------------- |
| POS_001 | 404  | Position not found                                | Invalid position ID              |
| POS_002 | 422  | Invalid wage type                                 | wage_type not daily/monthly      |
| POS_003 | 409  | Cannot reduce headcount below filled positions    | new_headcount < filled_count     |
| POS_004 | 409  | Cannot delete position with existing applications | Delete attempt with applications |
| POS_005 | 422  | Wage amount cannot be negative                    | Negative wage_amount             |
| POS_006 | 403  | Cannot modify position of closed event            | Event status is CLOSED           |

---

## Performance Considerations

- **Indexes:** `event_staffing_positions.event_id`, `event_staffing_positions.is_announced`
- **Composite index:** `(event_id, is_announced)` for announced positions per event queries
- **Eager loading:** Always load `event` relationship when displaying positions with event context
- **Counting filled positions:** Use `withCount('participations')` instead of loading all participations

---

## Dependencies

### Required From Other Modules

| Module | What                       | Why                   |
| ------ | -------------------------- | --------------------- |
| Event  | `events` table             | event_id foreign key  |
| Event  | `EventRepositoryInterface` | Validate event exists |

### Provided To Other Modules

| Recipient                | What            | Purpose                        |
| ------------------------ | --------------- | ------------------------------ |
| EventPositionApplication | positions table | Applications target positions  |
| EventParticipation       | positions table | Participations have a position |
| EventContract            | wage data       | Calculate contract wages       |

### No External Package Dependencies

Uses only Laravel core + Event module.

---

## Next Steps After Building EventStaffingPosition Module

### Pre-Flight Checklist

- [ ] event_staffing_positions table migrated
- [ ] wages table migrated
- [ ] PositionSeeder executed (sample positions)
- [ ] PositionServiceProvider registered after EventServiceProvider
- [ ] Create position via API returns 201
- [ ] Announce position updates is_announced flag
- [ ] Headcount validation works (cannot reduce below filled)

### Immediate Next Module: EventStaffingGroup

**Why EventStaffingGroup next?**

- Groups organize workers within an event
- EventParticipations can be assigned to groups
- EventTasks can be assigned to groups
- Independent of positions but similar scope

**Build Order after Position:**

```
Event → EventStaffingPosition → EventStaffingGroup → EventRoleAssignment → EventPositionApplication → EventParticipation
```

### Integration Point to Test

After EventStaffingGroup is built, test:

1. Create group under same event
2. Position and group are independent but both belong to event
3. Later, participation can have both position and group

### Commands to Run

```bash
# Verify Position module
php artisan migrate:status | grep event_staffing_positions
php artisan tinker --execute="Modules\EventStaffingPosition\Domain\Position::find('position-id')"

# Create EventStaffingGroup module
php artisan module:make EventStaffingGroup

# Register provider after PositionServiceProvider
```

### Success Criteria

- [ ] Positions created under events
- [ ] Wage amounts stored correctly (daily/monthly)
- [ ] Headcount tracking accurate
- [ ] Announcement toggles work
- [ ] Applications can reference positions
- [ ] Participations can reference positions

## Notifications & Events

### Events Emitted

| Event               | When                             | Payload                           | Notification Recipient                                    |
| ------------------- | -------------------------------- | --------------------------------- | --------------------------------------------------------- |
| PositionCreated     | Position created                 | position_id, event_id, title      | None (internal)                                           |
| PositionAnnounced   | Position opened for applications | position_id, event_id, title      | All active users matching requirements                    |
| PositionUnannounced | Position closed                  | position_id, event_id, title      | None                                                      |
| HeadcountChanged    | Headcount updated                | position_id, old_count, new_count | None (broadcast to event channel for real-time UI update) |
| WageUpdated         | Wage amount or type changed      | position_id, old_wage, new_wage   | None                                                      |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class PositionAnnounced
{
    public function __construct(
        public readonly PositionId $positionId,
        public readonly EventId $eventId,
        public readonly string $title,
        public readonly Carbon $occurredAt,
    ) {}
}

final class HeadcountChanged
{
    public function __construct(
        public readonly PositionId $positionId,
        public readonly int $oldHeadcount,
        public readonly int $newHeadcount,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None. EventStaffingPosition module fires events but does not listen to external events.

### Broadcast Channel

When `PositionAnnounced` or `HeadcountChanged` occurs, Notification module broadcasts to:

- `private-event.{eventId}` channel — all event role holders see updated position availability

---

**EventStaffingPosition Module Specification Complete.**
