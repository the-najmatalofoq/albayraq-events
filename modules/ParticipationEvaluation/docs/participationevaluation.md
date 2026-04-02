# ParticipationEvaluation Module

## Module Purpose

The ParticipationEvaluation module manages daily performance evaluations for workers. Each evaluation is linked to a specific participation (worker in event) on a specific date. Evaluations include a score (0-10), optional notes, and can be locked to prevent modification. This module is critical for Event Closure Gate 1: every participation must have an evaluation for every working day before an event can be closed.

---

## Table Schema

### `participation_evaluations`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| event_participation_id | uuid | FOREIGN KEY → event_participations.id, CASCADE DELETE | Worker being evaluated |
| evaluator_id | uuid | FOREIGN KEY → users.id, RESTRICT | Supervisor/manager giving evaluation |
| date | date | NOT NULL | Date of evaluation (must be within event date range) |
| score | decimal(3,1) | NOT NULL | Performance score (0-10, one decimal) |
| notes | text | NULLABLE | Qualitative feedback |
| is_locked | boolean | DEFAULT: false | Prevents modification after final approval |
| locked_at | timestamp | NULLABLE | When evaluation was locked |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique Constraint:** `(event_participation_id, evaluator_id, date)` — one evaluation per worker per evaluator per day.

**Validation Rules:**
- `score` between 0 and 10 (inclusive)
- `date` must be within event start_date and end_date
- Cannot modify if is_locked = true

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_107500_create_participation_evaluations_table.php` | Wave 6 | #29 | event_participations |

**Position:** Wave 6 — after event_attendance_records, before participation_violations.

---

## Relations

### Foreign Keys
- `participation_evaluations.event_participation_id` → `event_participations.id` (CASCADE DELETE)
- `participation_evaluations.evaluator_id` → `users.id` (RESTRICT)

### Eloquent Relationships
```php
// EvaluationModel
public function participation(): BelongsTo  // EventParticipation
public function evaluator(): BelongsTo  // User
```

---

## Execution Order

**Build Sequence Position:** Wave 6, #29 — after attendance, before violations.

```
Wave 6:
  #27: event_attendance_records
  #28: attendance_barcodes
  #29: participation_evaluations ← YOU ARE HERE
  #30: violation_types
  #31: participation_violations
```

**Service Provider Registration:** After EventAttendance, before ParticipationViolation.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| EventParticipation | `participations` table, event date range | Validate date within event |
| User | `users` table | evaluator_id FK |

### What Evaluation Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| EventClosure Gate | Evaluation completeness | Gate 1: every day evaluated |
| EventExperienceCertificate | Average score | Certificate includes performance score |
| Reporting | Evaluation data | Worker performance analytics |

---

## Domain Entities

### Aggregate Root: `ParticipationEvaluation`

**Identity:** EvaluationId (UUID)

**Core Attributes:**
- **ParticipationId:** Reference to worker's participation
- **EvaluatorId:** Reference to supervisor giving evaluation
- **Date:** Date of evaluation
- **Score:** Decimal (0-10, one decimal)
- **Notes:** Optional text feedback
- **IsLocked:** Boolean — prevents modification
- **LockedAt:** Timestamp (when locked)

**Business Rules:**
- Evaluator must have supervisor/manager role for this event
- Score cannot exceed 10 (capped)
- Cannot evaluate future dates
- Cannot evaluate dates outside event range
- Once locked, evaluation is permanent
- Only project_manager can unlock (override)

### Value Objects
- **EvaluationId:** UUID wrapper
- **Score:** Decimal with range validation (0-10, step 0.5 recommended)
- **EvaluationDate:** Date with event range validation

### Repository Interface: `EvaluationRepositoryInterface`
- `save(Evaluation $evaluation): void`
- `findById(EvaluationId $id): ?Evaluation`
- `findByParticipation(ParticipationId $participationId): array` (ordered by date)
- `findByParticipationAndDate(ParticipationId $participationId, Date $date): ?Evaluation`
- `findByEvent(EventId $eventId): array`
- `findMissingDates(ParticipationId $participationId, DateRange $range): array` (for gate check)
- `getAverageScore(ParticipationId $participationId): float`
- `lockByParticipation(ParticipationId $participationId): void` (lock all for participation)
- `delete(EvaluationId $id): void`

### Domain Events
- `EvaluationCreated` — When evaluation recorded
- `EvaluationUpdated` — When score/notes changed (before lock)
- `EvaluationLocked` — When is_locked becomes true
- `EvaluationUnlocked` — Admin override (rare)

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `CreateEvaluation` | participation_id, evaluator_id, date, score, notes | Creates evaluation, validates date range |
| `UpdateEvaluation` | evaluation_id, score, notes | Updates (fails if locked) |
| `LockEvaluation` | evaluation_id, locked_by | Sets is_locked = true, locked_at = now |
| `LockAllParticipationsEvaluations` | participation_id, locked_by | Locks all evaluations for a participation |
| `UnlockEvaluation` | evaluation_id, unlocked_by | Admin override (system_controller only) |
| `DeleteEvaluation` | evaluation_id | Deletes (fails if locked) |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetEvaluation` | evaluation_id | Full evaluation with participation, evaluator |
| `ListEvaluationsByParticipation` | participation_id | All evaluations for worker |
| `GetParticipationEvaluationSummary` | participation_id | Average score, total evaluations, missing dates |
| `CheckClosureGate1` | event_id | Boolean: all participations have all dates evaluated |

---

## API Endpoints

Base path: `/api/v1/participations/{participation_id}/evaluations`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/` | CreateEvaluationAction | Required | supervisor, site_manager, area_manager, project_manager |
| GET | `/` | ListEvaluationsAction | Required | As above + worker (own) |
| GET | `/{id}` | GetEvaluationAction | Required | As above |
| PUT | `/{id}` | UpdateEvaluationAction | Required | supervisor (own evaluations), project_manager |
| POST | `/{id}/lock` | LockEvaluationAction | Required | project_manager |
| POST | `/lock-all` | LockAllEvaluationsAction | Required | project_manager |
| DELETE | `/{id}` | DeleteEvaluationAction | Required | project_manager, system_controller |

### Request/Response Examples

**POST /participations/{participation_id}/evaluations**
Request:
```json
{
    "date": "2026-06-01",
    "score": 8.5,
    "notes": "Good performance, punctual, completed all tasks"
}
```
Response (201):
```json
{
    "id": "eval_uuid",
    "participation_id": "p_uuid",
    "date": "2026-06-01",
    "score": 8.5,
    "notes": "Good performance, punctual, completed all tasks",
    "is_locked": false,
    "evaluator": {
        "id": "supervisor_uuid",
        "name": {"ar": "مشرف", "en": "Supervisor"}
    }
}
```

**GET /participations/{participation_id}/evaluations**
Response:
```json
{
    "data": [
        {
            "id": "e1",
            "date": "2026-06-01",
            "score": 8.5,
            "notes": "Good performance",
            "is_locked": false
        },
        {
            "id": "e2",
            "date": "2026-06-02",
            "score": 7.0,
            "notes": "Late arrival",
            "is_locked": false
        }
    ],
    "summary": {
        "average_score": 7.75,
        "total_evaluations": 2,
        "missing_dates": ["2026-06-03"],
        "event_dates": ["2026-06-01", "2026-06-02", "2026-06-03"]
    }
}
```

**GET /participations/{participation_id}/evaluations/closure-gate**
Response:
```json
{
    "participation_id": "p_uuid",
    "event_id": "e_uuid",
    "event_dates": ["2026-06-01", "2026-06-02", "2026-06-03"],
    "evaluated_dates": ["2026-06-01", "2026-06-02"],
    "missing_dates": ["2026-06-03"],
    "gate_passed": false,
    "message": "Missing evaluations for 1 date(s)"
}
```

---

## Presenters API Response Format

### EvaluationPresenter
- id, date, score, notes, is_locked, locked_at
- evaluator (id, name)
- created_at, updated_at

### EvaluationSummaryPresenter (for dashboard)
- participation_id
- average_score
- total_evaluations
- missing_dates array
- gate_passed boolean

---

## Seeder Data

### EvaluationSeeder
Creates sample evaluations:

| Participation | Date Range | Scores | Locked |
|---------------|------------|--------|--------|
| Employee 1 @ Tech Conf | June 1-3 | 8.5, 9.0, 8.0 | Yes |
| Employee 2 @ Tech Conf | June 1-3 | 7.0, 6.5, 7.5 | Yes |
| Employee 3 @ Tech Conf | June 1-2 | 5.0, 6.0 | No (missing June 3) |

**Dependencies:** Requires participations.

**Run order:** After ParticipationSeeder.

---

## Infrastructure Implementation

### Eloquent Model: EvaluationModel
- Table: `participation_evaluations`
- Casts: `score` → decimal, `date` → date, `is_locked` → boolean, `locked_at` → datetime
- Relationships: `participation()`, `evaluator()`

### EloquentEvaluationRepository
Implements EvaluationRepositoryInterface.

**Key methods:**
- `save()` → creates/updates evaluation
- `findMissingDates()` → compares event date range with existing evaluations
- `getAverageScore()` → aggregate query
- `lockByParticipation()` → bulk update

### Reflector: EvaluationReflector
Converts between EvaluationModel and Evaluation domain entity.

---

## Service Provider Registration

**Class:** `Modules\ParticipationEvaluation\Infrastructure\Providers\ParticipationEvaluationServiceProvider`

**Register method:** Binds EvaluationRepositoryInterface to EloquentEvaluationRepository

**Boot method:** Loads migrations, loads routes

**Position:** After EventAttendance, before ParticipationViolation.

---

## Testing Strategy

### Unit Tests
- Score range validation (0-10)
- Date range validation (within event)
- Lock prevents updates
- Average score calculation

### Feature Tests
- Create evaluation → 201
- Create evaluation for future date → 422
- Update locked evaluation → 422
- Lock evaluation → is_locked = true
- Non-supervisor cannot create → 403
- Closure gate detection → missing dates identified

### Integration Tests
- Evaluation + Participation: evaluations belong to participation
- Evaluation + Event: date range validated against event
- Closure Gate 1: event cannot close with missing evaluations

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Create evaluation | supervisor, site_manager, area_manager, project_manager (for own event) |
| Update evaluation | supervisor (own), project_manager |
| Lock evaluation | project_manager |
| Unlock evaluation | system_controller only |
| View own evaluations | Worker (self) |
| View all evaluations | project_manager, area_manager, general_manager |

### Validation Rules

**CreateEvaluation:**
- `participation_id`: required, exists, participation must be active
- `date`: required, date, must be within event date range
- `score`: required, numeric, between:0,10
- `notes`: nullable, string, max:1000

**Business Rules:**
- Evaluator must have supervisor/manager role for this event
- Cannot create duplicate evaluation for same (participation, date, evaluator)
- Cannot evaluate after event is CLOSED

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| EvaluationCreated | After save | evaluation_id, participation_id, date, score | Update participation average score |
| EvaluationLocked | is_locked → true | evaluation_id, locked_by | Prevent further changes |
| AllEvaluationsLocked | Bulk lock | participation_id, locked_by | Mark participation evaluations complete |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| EVAL_001 | 404 | Evaluation not found | Invalid ID |
| EVAL_002 | 422 | Invalid score | Not between 0-10 |
| EVAL_003 | 422 | Date outside event range | Date < start_date or > end_date |
| EVAL_004 | 409 | Evaluation already exists for this date | Duplicate (participation, date, evaluator) |
| EVAL_005 | 409 | Cannot modify locked evaluation | Update when is_locked = true |
| EVAL_006 | 403 | Cannot evaluate future dates | Date > today |
| EVAL_007 | 403 | Evaluator lacks permission | User not supervisor for this event |

---

## Performance Considerations

- **Indexes:** `(participation_id, date)`, `evaluator_id`, `is_locked`, `date`
- **Composite index:** `(participation_id, is_locked)` for gate checks
- **Average score:** Cache for 1 hour (invalidated on new evaluation)
- **Missing dates calculation:** Use date range generation in memory (event has max 365 days)

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| EventParticipation | `participations` table, event date range | Validate date, link evaluation |
| User | `users` table | evaluator_id FK |
| EventRoleAssignment | supervisor role | Authorization for evaluators |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| EventClosure Gate | Evaluation completeness | Gate 1 validation |
| EventExperienceCertificate | Average score | Certificate generation |
| Reporting | Evaluation data | Performance analytics |

---

## Next Steps After Building ParticipationEvaluation Module

### Pre-Flight Checklist
- [ ] participation_evaluations table migrated
- [ ] Score range validation working (0-10)
- [ ] Date range validation working
- [ ] Lock prevents updates
- [ ] Missing dates detection works
- [ ] Gate 1 check returns correct boolean

### Immediate Next Module: ParticipationViolation

**Why ParticipationViolation next?**
- Violations affect worker record and wages
- Uses participation_id from EventParticipation
- Independent from evaluations but similar scope

### Build Order
```
ParticipationEvaluation → ParticipationViolation → Discount → EventTask
```

### Commands to Run
```bash
php artisan migrate:status | grep participation_evaluations
php artisan module:make ParticipationViolation
```

### Success Criteria
- [ ] Supervisors can evaluate workers daily
- [ ] Scores stored with one decimal precision
- [ ] Locked evaluations cannot be modified
- [ ] Missing evaluations detected for closure gate
- [ ] Average score calculated correctly

---

## Notifications & Events

### Events Emitted

| Event | When | Payload | Notification Recipient |
|-------|------|---------|------------------------|
| EvaluationCreated | Evaluation recorded | evaluation_id, participation_id, user_id, date, score, evaluator_id | Worker (the evaluated employee) |
| EvaluationUpdated | Score/notes changed | evaluation_id, old_score, new_score, updated_by | Worker (if score changed) |
| EvaluationLocked | Evaluation locked | evaluation_id, locked_by, locked_at | Worker, evaluator |
| AllEvaluationsLocked | All evaluations for participation locked | participation_id, user_id, locked_by | Worker |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class EvaluationCreated
{
    public function __construct(
        public readonly EvaluationId $evaluationId,
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly Date $date,
        public readonly float $score,
        public readonly UserId $evaluatorId,
        public readonly Carbon $occurredAt,
    ) {}
}

final class EvaluationUpdated
{
    public function __construct(
        public readonly EvaluationId $evaluationId,
        public readonly float $oldScore,
        public readonly float $newScore,
        public readonly UserId $updatedBy,
        public readonly Carbon $occurredAt,
    ) {}
}

final class EvaluationLocked
{
    public function __construct(
        public readonly EvaluationId $evaluationId,
        public readonly UserId $lockedBy,
        public readonly Carbon $lockedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class AllEvaluationsLocked
{
    public function __construct(
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly UserId $lockedBy,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None. ParticipationEvaluation module fires events but does not listen to external events.


**ParticipationEvaluation Module Specification Complete.**
