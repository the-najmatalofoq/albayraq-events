# EventPositionApplication Module

## Module Purpose

The EventPositionApplication module manages worker applications for event positions. When an event organizer announces a position (is_announced = true), workers can apply for that position. Applications have a status flow: pending → accepted / rejected / withdrawn. The module tracks ranking scores (for merit-based selection), rejection reasons, and review timestamps. This is the gateway to becoming an event participant — only accepted applications can be converted to participations.

---

## Table Schema

### `event_position_applications`

| Column           | Type         | Constraints                                               | Description                            |
| ---------------- | ------------ | --------------------------------------------------------- | -------------------------------------- |
| id               | uuid         | PRIMARY KEY                                               | Auto-generated UUID                    |
| user_id          | uuid         | FOREIGN KEY → users.id, RESTRICT                          | Worker applying                        |
| position_id      | uuid         | FOREIGN KEY → event_staffing_positions.id, CASCADE DELETE | Position being applied for             |
| status           | string       | DEFAULT: 'pending'                                        | pending, accepted, rejected, withdrawn |
| ranking_score    | decimal(5,2) | NULLABLE                                                  | Score for merit-based ranking (0-100)  |
| rejection_reason | text         | NULLABLE                                                  | Why application was rejected           |
| applied_at       | timestamp    | NOT NULL                                                  | When application was submitted         |
| reviewed_at      | timestamp    | NULLABLE                                                  | When application was reviewed          |
| reviewed_by      | uuid         | FOREIGN KEY → users.id, NULLABLE                          | Admin who reviewed                     |
| created_at       | timestamp    | NOT NULL                                                  |                                        |
| updated_at       | timestamp    | NOT NULL                                                  |                                        |

**Unique Constraint:** `(user_id, position_id)` — worker cannot apply twice for same position.

**Status Flow:**

```
pending → accepted (by admissions_admin)
pending → rejected (by admissions_admin)
pending → withdrawn (by worker)
accepted → (terminal)
rejected → (terminal)
withdrawn → (terminal)
```

---

## Migration Details

| Migration File                                                   | Wave   | Order | Dependencies                    |
| ---------------------------------------------------------------- | ------ | ----- | ------------------------------- |
| `2026_03_25_105000_create_event_position_applications_table.php` | Wave 4 | #20   | users, event_staffing_positions |

**Position:** Wave 4 — after questions, before event_participations.

---

## Relations

### Foreign Keys

- `event_position_applications.user_id` → `users.id` (RESTRICT)
- `event_position_applications.position_id` → `event_staffing_positions.id` (CASCADE DELETE)
- `event_position_applications.reviewed_by` → `users.id` (SET NULL)

### Eloquent Relationships

```php
// ApplicationModel
public function user(): BelongsTo
public function position(): BelongsTo
public function reviewer(): BelongsTo
```

---

## Execution Order

**Build Sequence Position:** Wave 4, #20 — after questions, before participations.

```
Wave 4:
  #19: questions
  #20: event_position_applications ← YOU ARE HERE

Wave 5:
  #21: event_participations (depends on accepted applications)
```

**Service Provider Registration:** After Question, before EventParticipation.

---

## What's Needed From Others

### Required Modules

| Module                | What                                 | Why                                                               |
| --------------------- | ------------------------------------ | ----------------------------------------------------------------- |
| User                  | `users` table, User entity           | user_id, reviewed_by FK                                           |
| EventStaffingPosition | `positions` table, is_announced flag | position_id FK, only announced positions can receive applications |

### What Application Module Provides to Others

| Recipient           | What                  | Purpose                          |
| ------------------- | --------------------- | -------------------------------- |
| EventParticipation  | Accepted applications | Convert to participation         |
| EventRoleAssignment | admissions_admin role | Who can review applications      |
| Reporting           | Application data      | Hiring metrics, acceptance rates |

---

## Domain Entities

### Aggregate Root: `PositionApplication`

**Identity:** ApplicationId (UUID)

**Core Attributes:**

- **UserId:** Reference to worker
- **PositionId:** Reference to position
- **Status:** ApplicationStatus enum
- **RankingScore:** Decimal (0-100) — null until reviewed
- **RejectionReason:** Optional text
- **AppliedAt:** Timestamp
- **ReviewedAt:** Timestamp (null until reviewed)
- **ReviewedBy:** UserId (null until reviewed)

**Business Rules:**

- Can only apply to announced positions (is_announced = true)
- Cannot apply twice to same position
- Cannot apply if already participating in event (check via EventParticipation module)
- Only admissions_admin or project_manager can accept/reject
- Accepted applications cannot be modified
- Ranking score only set on acceptance (merit order)

### Value Objects

- **ApplicationId:** UUID wrapper
- **ApplicationStatusEnum:** PENDING, ACCEPTED, REJECTED, WITHDRAWN

### Repository Interface: `ApplicationRepositoryInterface`

- `save(Application $application): void`
- `findById(ApplicationId $id): ?Application`
- `findByPosition(PositionId $positionId): array`
- `findByUser(UserId $userId): array`
- `findPendingByPosition(PositionId $positionId): array` (ordered by ranking_score DESC)
- `countAcceptedByPosition(PositionId $positionId): int`
- `delete(ApplicationId $id): void`

### Domain Events

- `ApplicationSubmitted` — When worker applies
- `ApplicationAccepted` — When status → ACCEPTED
- `ApplicationRejected` — When status → REJECTED
- `ApplicationWithdrawn` — When worker withdraws

---

## CQRS Commands

### Commands (Write)

| Command               | Input                                         | Behavior                                                                 |
| --------------------- | --------------------------------------------- | ------------------------------------------------------------------------ |
| `SubmitApplication`   | user_id, position_id                          | Creates application with PENDING status, validates position is announced |
| `AcceptApplication`   | application_id, ranking_score, reviewed_by    | Changes status to ACCEPTED, sets reviewed_at, ranking_score              |
| `RejectApplication`   | application_id, rejection_reason, reviewed_by | Changes status to REJECTED, sets reviewed_at                             |
| `WithdrawApplication` | application_id, withdrawn_by                  | Changes status to WITHDRAWN (worker self-service)                        |
| `UpdateRankingScore`  | application_id, ranking_score                 | Updates score (only if status = PENDING)                                 |

### Queries (Read)

| Query                        | Input                      | Output                               |
| ---------------------------- | -------------------------- | ------------------------------------ |
| `GetApplication`             | application_id             | Full application with user, position |
| `ListApplicationsByPosition` | position_id, status filter | Paginated applications               |
| `ListApplicationsByUser`     | user_id                    | Worker's application history         |
| `GetPendingCountByPosition`  | position_id                | Count for headfill monitoring        |

---

## API Endpoints

Base path: `/api/v1`

| Method | URI                               | Action                      | Auth     | Roles Allowed                                      |
| ------ | --------------------------------- | --------------------------- | -------- | -------------------------------------------------- |
| POST   | `/positions/{position_id}/apply`  | SubmitApplicationAction     | Required | Worker (self)                                      |
| GET    | `/my-applications`                | MyApplicationsAction        | Required | Worker (self)                                      |
| GET    | `/events/{event_id}/applications` | ListEventApplicationsAction | Required | admissions_admin, project_manager, general_manager |
| GET    | `/applications/{id}`              | GetApplicationAction        | Required | Worker (own), admissions_admin, project_manager    |
| POST   | `/applications/{id}/accept`       | AcceptApplicationAction     | Required | admissions_admin, project_manager                  |
| POST   | `/applications/{id}/reject`       | RejectApplicationAction     | Required | admissions_admin, project_manager                  |
| POST   | `/applications/{id}/withdraw`     | WithdrawApplicationAction   | Required | Worker (own)                                       |
| PUT    | `/applications/{id}/score`        | UpdateRankingScoreAction    | Required | admissions_admin, project_manager                  |

### Request/Response Examples

**POST /positions/{position_id}/apply**
Request:

```json
{
    "user_id": "user_uuid"
}
```

Response (201):

```json
{
    "id": "application_uuid",
    "status": "pending",
    "applied_at": "2026-03-31T10:00:00Z",
    "message": "Application submitted successfully"
}
```

**POST /applications/{id}/accept**
Request:

```json
{
    "ranking_score": 85.5
}
```

Response (200):

```json
{
    "id": "application_uuid",
    "status": "accepted",
    "ranking_score": 85.5,
    "reviewed_at": "2026-03-31T11:00:00Z",
    "message": "Application accepted. Creating participation..."
}
```

**GET /my-applications**
Response:

```json
{
    "data": [
        {
            "id": "app1",
            "position": {
                "id": "pos1",
                "title": {"ar": "حارس أمن", "en": "Security Guard"},
                "event": {"id": "e1", "name": {"ar": "مؤتمر", "en": "Conference"}}
            },
            "status": "pending",
            "applied_at": "2026-03-30T10:00:00Z"
        },
        {
            "id": "app2",
            "position": {...},
            "status": "accepted",
            "ranking_score": 92.0,
            "applied_at": "2026-03-28T10:00:00Z",
            "reviewed_at": "2026-03-29T10:00:00Z"
        }
    ]
}
```

---

## Presenters API Response Format

### ApplicationPresenter

- id, status, applied_at, reviewed_at
- ranking_score (if accepted)
- rejection_reason (if rejected)
- Embedded user summary (id, name, phone)
- Embedded position summary (title, event)
- Embedded reviewer (id, name) if reviewed

### ApplicationSummaryPresenter (for list views)

- id, status, applied_at, position title, ranking_score (if accepted)

---

## Seeder Data

### ApplicationSeeder

Creates sample applications:

| Worker     | Position                     | Status    | Ranking Score |
| ---------- | ---------------------------- | --------- | ------------- |
| Employee 1 | Security Guard (Tech Conf)   | accepted  | 85.0          |
| Employee 2 | Security Guard (Tech Conf)   | accepted  | 78.0          |
| Employee 3 | Security Guard (Tech Conf)   | rejected  | 45.0          |
| Employee 4 | Ticket Inspector (Tech Conf) | pending   | null          |
| Employee 5 | Coordinator (Marketing Expo) | accepted  | 92.0          |
| Employee 6 | Loader (Marketing Expo)      | withdrawn | null          |

**Dependencies:** Requires users, positions (with is_announced = true)

**Run order:** After PositionSeeder, before ParticipationSeeder.

---

## Infrastructure Implementation

### Eloquent Model: ApplicationModel

- Table: `event_position_applications`
- Casts: `ranking_score` → decimal, `applied_at` → datetime, `reviewed_at` → datetime
- Relationships: `user()`, `position()`, `reviewer()`

### EloquentApplicationRepository

Implements ApplicationRepositoryInterface.

**Key methods:**

- `save()` → creates/updates application
- `findPendingByPosition()` → ordered by ranking_score DESC (merit order)
- `countAcceptedByPosition()` → for headcount validation before creating participation

### Reflector: ApplicationReflector

Converts between ApplicationModel and Application domain entity.

---

## Service Provider Registration

**Class:** `Modules\EventPositionApplication\Infrastructure\Providers\EventPositionApplicationServiceProvider`

**Register method:** Binds ApplicationRepositoryInterface to EloquentApplicationRepository

**Boot method:** Loads migrations, loads routes

**Position:** After Question, before EventParticipation.

---

## Testing Strategy

### Unit Tests

- Cannot apply to unannounced position → exception
- Duplicate application → unique constraint violation
- Status transitions (pending → accepted → terminal)
- Cannot accept rejected application

### Feature Tests

- Apply to announced position → 201
- Apply to unannounced position → 422
- Accept application → status = accepted, ranking_score set
- Reject application → status = rejected, reason stored
- Worker withdraws → status = withdrawn
- Non-admissions_admin cannot accept → 403
- Accepted application auto-creates participation (integration)

### Integration Tests

- Application + Position: only announced positions
- Application + Participation: accepted application can be converted
- Application + EventRoleAssignment: only admissions_admin can review

---

## Security and Validation Rules

### Authorization Rules

| Action                          | Required Role                                      |
| ------------------------------- | -------------------------------------------------- |
| Submit application              | Worker (self)                                      |
| View own applications           | Worker (self)                                      |
| View all applications for event | admissions_admin, project_manager, general_manager |
| Accept/reject application       | admissions_admin, project_manager                  |
| Withdraw application            | Worker (self)                                      |
| Update ranking score            | admissions_admin, project_manager                  |

### Validation Rules

**SubmitApplication:**

- `user_id`: required, exists:users, user must be active
- `position_id`: required, exists:positions
- Position must have is_announced = true
- User cannot have existing accepted application for same event (via participation check)

**AcceptApplication:**

- `ranking_score`: required, numeric, between:0,100
- Application status must be PENDING
- Position headcount must not be full

---

## Events Emitted

| Event                | When               | Payload                              | Listeners                                |
| -------------------- | ------------------ | ------------------------------------ | ---------------------------------------- |
| ApplicationSubmitted | After submit       | application_id, user_id, position_id | Notify admissions_admin                  |
| ApplicationAccepted  | Status → ACCEPTED  | application_id, ranking_score        | Auto-create participation, notify worker |
| ApplicationRejected  | Status → REJECTED  | application_id, reason               | Notify worker                            |
| ApplicationWithdrawn | Status → WITHDRAWN | application_id                       | Free up spot for other applicants        |

---

## Error Handling

| Code    | HTTP | Message                             | When                        |
| ------- | ---- | ----------------------------------- | --------------------------- |
| APP_001 | 404  | Application not found               | Invalid ID                  |
| APP_002 | 422  | Position not announced              | Cannot apply to unannounced |
| APP_003 | 409  | Already applied to this position    | Duplicate                   |
| APP_004 | 422  | Position headcount full             | Cannot accept more          |
| APP_005 | 422  | Already participating in this event | User already assigned       |
| APP_006 | 422  | Cannot modify accepted application  | Update after acceptance     |
| APP_007 | 403  | Cannot review own application       | Self-review attempt         |

---

## Performance Considerations

- **Indexes:** `(position_id, status)`, `(user_id, position_id)` unique, `status`, `ranking_score`
- **Composite index:** `(position_id, ranking_score DESC)` for merit ordering
- **Caching:** Pending count per position cached for 5 minutes
- **Batch acceptance:** Use queue for auto-creating participations

---

## Dependencies

### Required From Other Modules

| Module                | What                            | Why            |
| --------------------- | ------------------------------- | -------------- |
| User                  | `users` table                   | user_id FK     |
| EventStaffingPosition | `positions` table, is_announced | position_id FK |

### Provided To Other Modules

| Recipient           | What                  | Purpose                     |
| ------------------- | --------------------- | --------------------------- |
| EventParticipation  | Accepted applications | Create participation record |
| EventRoleAssignment | admissions_admin role | Review authorization        |

---

## Next Steps After Building EventPositionApplication Module

### Pre-Flight Checklist

- [ ] event_position_applications table migrated
- [ ] Unique constraint (user_id, position_id) working
- [ ] Can apply only to announced positions
- [ ] Accept/reject flows work
- [ ] Ranking scores stored correctly
- [ ] Accepted applications trigger participation creation

### Immediate Next Module: Quiz

**Why Quiz next?**

- Quizzes are used for contract acceptance (pass_quiz step)
- Questions belong to quizzes
- EmployeeQuizAttempt tracks worker quiz performance

### Build Order

```
EventPositionApplication → Quiz → Question → EmployeeQuizAttempt → EmployeeAnswer → ParticipationEvaluation
```

### Commands to Run

```bash
php artisan migrate:status | grep event_position_applications
php artisan module:make Quiz
```

### Success Criteria

- [ ] Workers can apply to announced positions
- [ ] Admissions_admin can review and rank applicants
- [ ] Accepted applications become participations
- [ ] Rejected applications notify worker
- [ ] Ranking scores visible for merit selection

## Notifications & Events

### Events Emitted

| Event | When | Payload | Notification Recipient |
|-------|------|---------|------------------------|
| ApplicationSubmitted | Worker applies | application_id, user_id, position_id, position_title, event_name | Admissions admin, project manager |
| ApplicationAccepted | Application accepted | application_id, user_id, position_id, ranking_score | Worker (applicant) |
| ApplicationRejected | Application rejected | application_id, user_id, position_id, rejection_reason | Worker (applicant) |
| ApplicationWithdrawn | Worker withdraws | application_id, user_id, position_id | Admissions admin, project manager |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class ApplicationSubmitted
{
    public function __construct(
        public readonly ApplicationId $applicationId,
        public readonly UserId $userId,
        public readonly PositionId $positionId,
        public readonly string $positionTitle,
        public readonly string $eventName,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ApplicationAccepted
{
    public function __construct(
        public readonly ApplicationId $applicationId,
        public readonly UserId $userId,
        public readonly PositionId $positionId,
        public readonly float $rankingScore,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ApplicationRejected
{
    public function __construct(
        public readonly ApplicationId $applicationId,
        public readonly UserId $userId,
        public readonly PositionId $positionId,
        public readonly string $rejectionReason,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ApplicationWithdrawn
{
    public function __construct(
        public readonly ApplicationId $applicationId,
        public readonly UserId $userId,
        public readonly PositionId $positionId,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None. EventPositionApplication module fires events but does not listen to external events.

### Broadcast Channel

When `ApplicationAccepted` occurs, Notification module broadcasts to:

- `private-event.{eventId}` channel — all event role holders see updated position filled count
- `private-user.{userId}` channel — applicant receives real-time acceptance notification


**EventPositionApplication Module Specification Complete.**

