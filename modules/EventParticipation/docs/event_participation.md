# EventParticipation Module

## Module Purpose

The EventParticipation module is the **central pivot** of the entire Event Management System. It links a user (worker) to an event with a specific position and optional group. Every operation — contracts, attendance, evaluations, violations, badges, certificates — hangs off this table. A worker participating in 3 events has 3 separate participation records, each with its own contract, attendance history, evaluation scores, and earned badges.

This module represents the employment relationship between a worker and an event. Without participation, a worker cannot sign contracts, clock attendance, receive evaluations, or get paid.

---

## Table Schema

### `event_participations`

| Column          | Type      | Constraints                                         | Description                           |
| --------------- | --------- | --------------------------------------------------- | ------------------------------------- |
| id              | uuid      | PRIMARY KEY                                         | Auto-generated UUID                   |
| user_id         | uuid      | FOREIGN KEY → users.id, RESTRICT                    | Worker participating in event         |
| event_id        | uuid      | FOREIGN KEY → events.id, RESTRICT                   | Event being worked                    |
| position_id     | uuid      | FOREIGN KEY → event_staffing_positions.id, RESTRICT | Job position held                     |
| group_id        | uuid      | FOREIGN KEY → event_staffing_groups.id, NULLABLE    | Team/group assignment (optional)      |
| employee_number | string    | NULLABLE                                            | Internal ID for payroll/HR systems    |
| status          | string    | DEFAULT: 'active'                                   | active, completed, cancelled, no_show |
| started_at      | date      | NULLABLE                                            | First day worked                      |
| ended_at        | date      | NULLABLE                                            | Last day worked                       |
| created_at      | timestamp | NOT NULL                                            |                                       |
| updated_at      | timestamp | NOT NULL                                            |                                       |
| deleted_at      | timestamp | NULLABLE                                            | Soft delete                           |

**Unique Constraint:** `(user_id, event_id)` — A user cannot participate in the same event twice.

**Status Values:**

- `active` — Currently working in the event
- `completed` — Finished all assigned days, contract fulfilled
- `cancelled` — Terminated before event end (admin action)
- `no_show` — Registered but never attended

**Validation Rules:**

- `started_at` <= `ended_at` (if both provided)
- `ended_at` cannot be before event start date
- Cannot have active participation for same user+event

---

## Migration Details

| Migration File                                            | Wave   | Order | Dependencies                                                   |
| --------------------------------------------------------- | ------ | ----- | -------------------------------------------------------------- |
| `2026_03_25_105500_create_event_participations_table.php` | Wave 5 | #21   | users, events, event_staffing_positions, event_staffing_groups |

**Position:** Wave 5 — after applications, before contracts and attendance.

**Why Wave 5?** Participations require:

1. Users (Wave 1)
2. Events (Wave 2)
3. Positions (Wave 3)
4. Groups (Wave 3)
5. Applications (Wave 4) — optional but logical order

---

## Relations

### Foreign Keys

- `event_participations.user_id` → `users.id` (RESTRICT)
- `event_participations.event_id` → `events.id` (RESTRICT)
- `event_participations.position_id` → `event_staffing_positions.id` (RESTRICT)
- `event_participations.group_id` → `event_staffing_groups.id` (SET NULL on delete)

### Tables That Reference Participations (External)

| Table                         | Foreign Key            | Module                     |
| ----------------------------- | ---------------------- | -------------------------- |
| event_contracts               | event_participation_id | EventContract              |
| event_attendance_records      | event_participation_id | EventAttendance            |
| participation_evaluations     | event_participation_id | ParticipationEvaluation    |
| participation_violations      | event_participation_id | ParticipationViolation     |
| event_participation_badges    | event_participation_id | EventParticipationBadge    |
| event_experience_certificates | event_participation_id | EventExperienceCertificate |
| employee_quiz_attempts        | event_participation_id | EmployeeQuizAttempt        |
| event_asset_custodies         | event_participation_id | EventAssetCustody          |

### Eloquent Relationships (in ParticipationModel)

```php
public function user(): BelongsTo  // User module
public function event(): BelongsTo  // Event module
public function position(): BelongsTo  // EventStaffingPosition module
public function group(): BelongsTo  // EventStaffingGroup module
public function contract(): HasOne  // EventContract module
public function attendanceRecords(): HasMany  // EventAttendance module
public function evaluations(): HasMany  // ParticipationEvaluation module
public function violations(): HasMany  // ParticipationViolation module
public function badge(): HasOne  // EventParticipationBadge module
public function certificate(): HasOne  // EventExperienceCertificate module
```

---

## Execution Order

**Build Sequence Position:** Wave 5, #21 — after applications, before contracts.

```
Wave 4:
  #19: questions
  #20: event_position_applications

Wave 5:
  #21: event_participations ← YOU ARE HERE

Wave 6:
  #22: digital_signatures
  #23: event_contracts (depends on participations)
  #24: contract_acceptance_steps
  #25: employee_quiz_attempts
  #26: employee_answers
  #27: event_attendance_records (depends on participations)
  #28: attendance_barcodes
  #29: participation_evaluations (depends on participations)
  #30: violation_types
  #31: participation_violations (depends on participations)
  #32: discounts
  #33: event_participation_badges (depends on participations)
  #34: event_experience_certificates (depends on participations)
```

**Service Provider Registration:** After EventStaffingPosition, before EventContract.

```php
return [
    Modules\EventStaffingPosition\Infrastructure\Providers\EventStaffingPositionServiceProvider::class,
    Modules\EventParticipation\Infrastructure\Providers\EventParticipationServiceProvider::class, // HERE
    Modules\EventContract\Infrastructure\Providers\EventContractServiceProvider::class,
];
```

---

## What's Needed From Others

### Required Modules (All Must Exist Before Building)

| Module                   | What                             | Why                                                       |
| ------------------------ | -------------------------------- | --------------------------------------------------------- |
| User                     | `users` table, User entity       | user_id foreign key                                       |
| Event                    | `events` table, Event entity     | event_id foreign key                                      |
| EventStaffingPosition    | `event_staffing_positions` table | position_id foreign key                                   |
| EventStaffingGroup       | `event_staffing_groups` table    | group_id foreign key (optional)                           |
| EventPositionApplication | Application domain               | Participation typically created from accepted application |

### What Participation Module Provides to Others

| Recipient                  | What                    | Purpose                                |
| -------------------------- | ----------------------- | -------------------------------------- |
| EventContract              | participation reference | Contract belongs to participation      |
| EventAttendance            | participation reference | Attendance records track participation |
| ParticipationEvaluation    | participation reference | Evaluations scored per participation   |
| ParticipationViolation     | participation reference | Violations recorded per participation  |
| EventParticipationBadge    | participation reference | Badges earned per participation        |
| EventExperienceCertificate | participation reference | Certificate issued per participation   |
| EmployeeQuizAttempt        | participation reference | Quiz attempts linked to participation  |
| EventAssetCustody          | participation reference | Assets checked out to participation    |

---

## Domain Entities

### Aggregate Root: `EventParticipation`

**Identity:** ParticipationId (UUID)

**Core Attributes:**

- **UserId:** Reference to worker
- **EventId:** Reference to event
- **PositionId:** Reference to job position
- **GroupId:** Optional reference to team/group
- **EmployeeNumber:** Optional internal ID
- **Status:** ParticipationStatus enum
- **StartedAt:** Date — first day worked
- **EndedAt:** Date — last day worked (null if active)

**Business Rules:**

- One participation per (user, event) pair — enforced by unique constraint
- Cannot create participation for inactive user
- Cannot create participation for non-active event (status must be ACTIVE or PUBLISHED)
- Cannot create participation if position headcount is full
- Status transitions: active → completed/cancelled/no_show (irreversible)
- Ended_at automatically set when status changes to completed/cancelled
- Cannot modify participation after event is CLOSED

### Value Objects

- **ParticipationId:** UUID wrapper
- **ParticipationStatus:** Enum (active, completed, cancelled, no_show)
- **EmployeeNumber:** Optional string, unique per event (application-generated)

### Enums

**ParticipationStatusEnum:**

- ACTIVE — Currently working
- COMPLETED — Finished successfully
- CANCELLED — Terminated by admin
- NO_SHOW — Registered but never attended

### Repository Interface: `ParticipationRepositoryInterface`

- `save(Participation $participation): void`
- `findById(ParticipationId $id): ?Participation`
- `findByUserAndEvent(UserId $userId, EventId $eventId): ?Participation`
- `findByEvent(EventId $eventId): array`
- `findByPosition(PositionId $positionId): array`
- `findActiveByUser(UserId $userId): array`
- `countByPosition(PositionId $positionId): int` (for headcount validation)
- `delete(ParticipationId $id): void`

### Domain Events

- `ParticipationCreated` — When worker is assigned to event (payload: participation_id, user_id, event_id, position_id)
- `ParticipationCompleted` — When status changes to COMPLETED
- `ParticipationCancelled` — When status changes to CANCELLED
- `ParticipationNoShow` — When status changes to NO_SHOW
- `GroupAssigned` — When group_id is set or changed

---

## CQRS Commands

### Commands (Write)

| Command                 | Input                                                                                       | Behavior                                                      |
| ----------------------- | ------------------------------------------------------------------------------------------- | ------------------------------------------------------------- |
| `CreateParticipation`   | user_id, event_id, position_id, group_id (optional), employee_number (optional), created_by | Creates participation with ACTIVE status, validates headcount |
| `AssignToGroup`         | participation_id, group_id                                                                  | Updates group assignment                                      |
| `CompleteParticipation` | participation_id, completed_by                                                              | Changes status to COMPLETED, sets ended_at = today            |
| `CancelParticipation`   | participation_id, cancelled_by, reason                                                      | Changes status to CANCELLED, sets ended_at = today            |
| `MarkNoShow`            | participation_id, marked_by                                                                 | Changes status to NO_SHOW                                     |
| `UpdateEmployeeNumber`  | participation_id, employee_number                                                           | Sets internal ID                                              |
| `DeleteParticipation`   | participation_id                                                                            | Soft delete (only if no contracts/attendance)                 |

### Queries (Read)

| Query                       | Input                                       | Output                                               |
| --------------------------- | ------------------------------------------- | ---------------------------------------------------- |
| `GetParticipation`          | participation_id                            | Full participation with user, event, position, group |
| `ListParticipationsByEvent` | event_id, filters (status, position, group) | Paginated participations                             |
| `ListParticipationsByUser`  | user_id                                     | All participations for a worker                      |
| `GetParticipationSummary`   | event_id                                    | Counts by status, position, group                    |

---

## API Endpoints

Base path: `/api/v1/events/{event_id}/participations`

| Method | URI              | Action                      | Auth     | Roles Allowed                                       |
| ------ | ---------------- | --------------------------- | -------- | --------------------------------------------------- |
| POST   | `/`              | CreateParticipationAction   | Required | system_controller, general_manager, project_manager |
| GET    | `/`              | ListParticipationsAction    | Required | As above + area_manager, site_manager (scoped)      |
| GET    | `/{id}`          | GetParticipationAction      | Required | As above + the worker themselves                    |
| PUT    | `/{id}/group`    | AssignToGroupAction         | Required | project_manager, area_manager, site_manager         |
| POST   | `/{id}/complete` | CompleteParticipationAction | Required | project_manager                                     |
| POST   | `/{id}/cancel`   | CancelParticipationAction   | Required | project_manager, system_controller                  |
| DELETE | `/{id}`          | DeleteParticipationAction   | Required | system_controller only                              |

### Request/Response Examples

**POST /events/{event_id}/participations**
Request:

```json
{
    "user_id": "user_uuid",
    "position_id": "position_uuid",
    "group_id": "group_uuid",
    "employee_number": "EMP-001"
}
```

Response (201):

```json
{
    "id": "participation_uuid",
    "status": "active",
    "message": "Worker assigned to event"
}
```

**GET /events/{event_id}/participations/{id}**
Response:

```json
{
    "id": "participation_uuid",
    "user": {
        "id": "user_uuid",
        "name": { "ar": "أحمد محمد", "en": "Ahmed Mohamed" },
        "phone": "+966501234567"
    },
    "event": {
        "id": "event_uuid",
        "name": { "ar": "مؤتمر التقنية", "en": "Tech Conference" }
    },
    "position": {
        "id": "position_uuid",
        "title": { "ar": "حارس أمن", "en": "Security Guard" }
    },
    "group": {
        "id": "group_uuid",
        "name": { "ar": "الفريق أ", "en": "Team A" }
    },
    "employee_number": "EMP-001",
    "status": "active",
    "started_at": "2026-06-01",
    "ended_at": null,
    "attendance_summary": {
        "total_days": 15,
        "present_days": 14,
        "absent_days": 1,
        "late_days": 2
    },
    "average_evaluation": 4.5
}
```

---

## Presenters API Response Format

### ParticipationPresenter

Transforms Participation domain object to API response:

- Embeds user summary (id, name, phone)
- Embeds event summary (id, name)
- Embeds position summary (id, title)
- Embeds group summary (id, name) if assigned
- Includes calculated fields: attendance_summary, average_evaluation

### ParticipationSummaryPresenter (for list views)

Reduced response: id, user name, position title, status, started_at, ended_at

---

## Seeder Data

### ParticipationSeeder

Creates sample participations for development:

| User       | Event           | Position         | Status    |
| ---------- | --------------- | ---------------- | --------- |
| Employee 1 | Tech Conference | Security Guard   | active    |
| Employee 2 | Tech Conference | Ticket Inspector | active    |
| Employee 3 | Tech Conference | Team Leader      | active    |
| Employee 4 | Marketing Expo  | Coordinator      | completed |
| Employee 5 | Marketing Expo  | Loader           | cancelled |

**Dependencies:** Requires users, events, positions to exist.

**Run order:** After PositionSeeder, before ContractSeeder.

---

## Infrastructure Implementation

### Eloquent Model: ParticipationModel

- Table: `event_participations`
- Casts: `started_at` → date, `ended_at` → date, `deleted_at` → datetime
- Relationships: `user()`, `event()`, `position()`, `group()`, `contract()`, `attendanceRecords()`, `evaluations()`, `violations()`, `badge()`, `certificate()`

### EloquentParticipationRepository

Implements ParticipationRepositoryInterface.

**Key methods:**

- `save()` → creates or updates ParticipationModel
- `countByPosition()` → used for headcount validation before creation
- `findByUserAndEvent()` → unique constraint enforcement
- `findActiveByUser()` → for dashboard "my active events"

### Reflector: ParticipationReflector

Converts between ParticipationModel and Participation domain entity:

- Model → Domain: reconstructs with UserId, EventId, PositionId, GroupId value objects
- Domain → Model: maps attributes

---

## Service Provider Registration

**Class:** `Modules\EventParticipation\Infrastructure\Providers\EventParticipationServiceProvider`

**Register method:** Binds ParticipationRepositoryInterface to EloquentParticipationRepository

**Boot method:** Loads migrations, loads routes

**Position in bootstrap/providers.php:** After EventStaffingPosition, before EventContract.

---

## Testing Strategy

### Unit Tests

- Participation creation with valid/invalid data
- Status transitions (active → completed, active → cancelled)
- Headcount validation (cannot exceed position headcount)
- Unique constraint (user+event)

### Feature Tests

- Create participation → 201, participation record created
- Create participation for full position → 422 (headcount exceeded)
- Create duplicate participation → 422 (unique constraint)
- Complete participation → status = completed, ended_at set
- Cancel participation → status = cancelled
- Delete participation with contract → 409 (RESTRICT)
- Non-project_manager cannot create → 403

### Integration Tests

- Participation + Contract: contract belongs to participation
- Participation + Attendance: attendance records filter by participation
- Participation + Evaluation: evaluations belong to participation
- Participation + Badge: badge generated on event closure

---

## Security and Validation Rules

### Authorization Rules

| Action                 | Required Role                                                                            |
| ---------------------- | ---------------------------------------------------------------------------------------- |
| Create participation   | project_manager (own event), general_manager, system_controller                          |
| View any participation | project_manager, area_manager, site_manager (scoped), general_manager, system_controller |
| View own participation | The worker themselves                                                                    |
| Assign to group        | project_manager, area_manager, site_manager                                              |
| Complete/cancel        | project_manager, system_controller                                                       |
| Delete participation   | system_controller only                                                                   |

### Validation Rules

**CreateParticipation:**

- `user_id`: required, exists:users, user must be active
- `event_id`: required, exists:events, event status must be PUBLISHED or ACTIVE
- `position_id`: required, exists:positions, position must belong to event
- `group_id`: nullable, exists:groups, group must belong to event
- `employee_number`: nullable, string, unique per event (if provided)

**Business Rules:**

- Cannot create participation if position headcount is reached
- Cannot create participation for user already participating in same event
- Cannot modify participation after event is CLOSED

---

## Events Emitted

| Event                  | When               | Payload                                          | Listeners                          |
| ---------------------- | ------------------ | ------------------------------------------------ | ---------------------------------- |
| ParticipationCreated   | After save         | participation_id, user_id, event_id, position_id | Create contract, send notification |
| ParticipationCompleted | Status → COMPLETED | participation_id, completed_at                   | Trigger final evaluation           |
| ParticipationCancelled | Status → CANCELLED | participation_id, cancelled_at, reason           | Cancel contract, revoke access     |
| GroupAssigned          | Group changed      | participation_id, old_group_id, new_group_id     | Notify group manager               |

---

## Error Handling

| Code     | HTTP | Message                                            | When                                 |
| -------- | ---- | -------------------------------------------------- | ------------------------------------ |
| PART_001 | 404  | Participation not found                            | Invalid participation ID             |
| PART_002 | 422  | User already participating in this event           | Duplicate user+event                 |
| PART_003 | 422  | Position headcount full                            | Cannot assign more workers           |
| PART_004 | 422  | Event not active for assignments                   | Event status not PUBLISHED or ACTIVE |
| PART_005 | 422  | User account is inactive                           | Cannot assign inactive user          |
| PART_006 | 409  | Cannot delete participation with existing contract | Delete attempt with contract         |
| PART_007 | 403  | Cannot modify participation after event closed     | Event status is CLOSED               |
| PART_008 | 422  | Invalid status transition                          | e.g., completed → active             |

---

## Performance Considerations

- **Indexes:** `(user_id, event_id)` unique, `event_id`, `position_id`, `group_id`, `status`
- **Composite index:** `(event_id, status)` for event dashboard queries
- **Counting by position:** Use `countByPosition()` method instead of loading all participations
- **Eager loading:** Always load `user`, `event`, `position` when displaying participation lists
- **Soft deletes:** Filter `whereNull('deleted_at')` in all queries

---

## Dependencies

### Required From Other Modules

| Module                | What                         | Why                     |
| --------------------- | ---------------------------- | ----------------------- |
| User                  | `users` table, User entity   | user_id foreign key     |
| Event                 | `events` table, Event entity | event_id foreign key    |
| EventStaffingPosition | `positions` table            | position_id foreign key |
| EventStaffingGroup    | `groups` table               | group_id foreign key    |

### Provided To Other Modules

| Recipient                  | What                    | Purpose                               |
| -------------------------- | ----------------------- | ------------------------------------- |
| EventContract              | participation reference | Contract belongs to participation     |
| EventAttendance            | participation reference | Attendance tracks participation       |
| ParticipationEvaluation    | participation reference | Evaluation per participation          |
| ParticipationViolation     | participation reference | Violations per participation          |
| EventParticipationBadge    | participation reference | Badges per participation              |
| EventExperienceCertificate | participation reference | Certificates per participation        |
| EmployeeQuizAttempt        | participation reference | Quiz attempts linked to participation |
| EventAssetCustody          | participation reference | Assets checked out to participation   |

---

## Next Steps After Building EventParticipation Module

### Pre-Flight Checklist

- [ ] event_participations table migrated
- [ ] Unique constraint (user_id, event_id) working
- [ ] ParticipationSeeder executed (sample participations)
- [ ] ParticipationServiceProvider registered
- [ ] Create participation via API returns 201
- [ ] Headcount validation prevents over-assignment
- [ ] Status transitions work correctly
- [ ] Cannot delete participation with contract (RESTRICT)

### Immediate Next Module: EventContract

**Why EventContract next?**

- Contract is the first dependent entity on participation
- Every active participation needs a contract
- Contract acceptance is the 5-step gate before work begins
- Attendance, evaluations, payments all require signed contract

**Build Order after Participation:**

```
Event → EventStaffingPosition → EventStaffingGroup → EventParticipation → EventContract → EventAttendance → ParticipationEvaluation
```

### Integration Point to Test

After EventContract module is built, test:

1. Participation created → contract automatically created
2. Contract status = pending
3. Worker goes through 5 acceptance steps
4. After acceptance, attendance can be recorded

### Commands to Run

```bash
# Verify Participation module
php artisan migrate:status | grep event_participations
php artisan tinker --execute="Modules\EventParticipation\Domain\EventParticipation::first()"

# Create EventContract module
php artisan module:make EventContract

# Register provider after ParticipationServiceProvider
```

### Success Criteria

- [ ] Participations link users to events with positions
- [ ] Headcount limits enforced
- [ ] Status workflow (active → completed/cancelled/no_show)
- [ ] Participation is the central pivot for all operations
- [ ] Other modules can reference participations via foreign keys

---

**EventParticipation Module Specification Complete.**
