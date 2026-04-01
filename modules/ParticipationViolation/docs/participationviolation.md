# ParticipationViolation Module

## Module Purpose

The ParticipationViolation module tracks worker violations during events. Violations are reported by supervisors and escalate through tiers: Tier 1 (supervisor) → Tier 2 (area_manager) → Tier 3 (project_manager). Each violation has a type (from ViolationType module), description, date, and deduction amount. The escalation system ensures fair review before financial penalties are applied.

---

## Table Schema

### `participation_violations`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| event_participation_id | uuid | FOREIGN KEY → event_participations.id, CASCADE DELETE | Worker who committed violation |
| violation_type_id | uuid | FOREIGN KEY → violation_types.id, RESTRICT | Type of violation |
| reported_by | uuid | FOREIGN KEY → users.id, RESTRICT | Supervisor who reported |
| description | text | NULLABLE | Additional details |
| date | date | NOT NULL | When violation occurred |
| current_tier | tinyint | DEFAULT: 1 | Escalation level (1, 2, 3) |
| status | string | DEFAULT: 'pending' | pending, escalated, approved, rejected |
| deduction_amount | decimal(10,2) | NULLABLE | Financial penalty (set on approval) |
| approved_by | uuid | FOREIGN KEY → users.id, NULLABLE | Manager who approved |
| approved_at | timestamp | NULLABLE | When approved/rejected |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Status Flow:**
```
pending → escalated (moved to next tier)
pending → approved (by current tier manager)
pending → rejected (by current tier manager)
escalated → approved/rejected (by higher tier)
```

**Tiers:**
- Tier 1: supervisor reviews and can approve (minor violations)
- Tier 2: area_manager reviews escalated violations
- Tier 3: project_manager reviews final escalation

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_108000_create_participation_violations_table.php` | Wave 6 | #31 | event_participations, violation_types |

**Position:** Wave 6 — after violation_types, before discounts.

---

## Relations

### Foreign Keys
- `participation_violations.event_participation_id` → `event_participations.id` (CASCADE DELETE)
- `participation_violations.violation_type_id` → `violation_types.id` (RESTRICT)
- `participation_violations.reported_by` → `users.id` (RESTRICT)
- `participation_violations.approved_by` → `users.id` (SET NULL)

### Eloquent Relationships
```php
// ViolationModel
public function participation(): BelongsTo
public function violationType(): BelongsTo
public function reporter(): BelongsTo  // User
public function approver(): BelongsTo  // User
```

---

## Execution Order

**Build Sequence Position:** Wave 6, #31 — after violation_types, before discounts.

```
Wave 6:
  #30: violation_types
  #31: participation_violations ← YOU ARE HERE
  #32: discounts
```

**Service Provider Registration:** After ViolationType, before Discount.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| EventParticipation | `participations` table | participation_id FK |
| ViolationType | `violation_types` table, default_deduction | violation_type_id FK |
| User | `users` table | reported_by, approved_by FK |
| EventRoleAssignment | supervisor/manager roles | Escalation authorization |

### What Violation Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| Discount | violation_id | Apply financial penalty |
| Payroll | deduction_amount | Reduce worker wages |
| Reporting | Violation data | Discipline analytics |

---

## Domain Entities

### Aggregate Root: `ParticipationViolation`

**Identity:** ViolationId (UUID)

**Core Attributes:**
- **ParticipationId:** Reference to worker
- **ViolationTypeId:** Reference to violation type
- **ReportedBy:** UserId of reporter
- **Description:** Optional text
- **Date:** When violation occurred
- **CurrentTier:** Integer (1, 2, or 3)
- **Status:** ViolationStatus enum
- **DeductionAmount:** Decimal (null until approved)
- **ApprovedBy:** UserId (null until approved)
- **ApprovedAt:** Timestamp (null until approved)

**Business Rules:**
- Violation escalates if current tier manager rejects
- Deduction amount defaults to violation_type.default_deduction
- Cannot modify after approval
- Only current tier manager can approve/reject
- Tier 3 (project_manager) decisions are final

### Value Objects
- **ViolationId:** UUID wrapper
- **ViolationStatusEnum:** PENDING, ESCALATED, APPROVED, REJECTED
- **ViolationTier:** 1, 2, 3 with escalation logic

### Repository Interface: `ViolationRepositoryInterface`
- `save(Violation $violation): void`
- `findById(ViolationId $id): ?Violation`
- `findByParticipation(ParticipationId $participationId): array`
- `findByStatus(ViolationStatus $status): array`
- `findPendingByTier(int $tier): array` (for manager review queues)
- `countByParticipation(ParticipationId $participationId): int`
- `getTotalDeductions(ParticipationId $participationId): float`

### Domain Events
- `ViolationReported` — When violation created
- `ViolationEscalated` — When tier increased
- `ViolationApproved` — When status → APPROVED, deduction set
- `ViolationRejected` — When status → REJECTED

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `ReportViolation` | participation_id, violation_type_id, reported_by, description, date | Creates violation with PENDING status, tier 1 |
| `EscalateViolation` | violation_id, escalated_by | Increases current_tier by 1, status → ESCALATED |
| `ApproveViolation` | violation_id, approved_by, deduction_amount (optional) | Status → APPROVED, sets deduction (default from type) |
| `RejectViolation` | violation_id, rejected_by, reason | Status → REJECTED |
| `UpdateViolation` | violation_id, description | Updates (only if pending) |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetViolation` | violation_id | Full violation with type, reporter, approver |
| `ListViolationsByParticipation` | participation_id | All violations for worker |
| `ListPendingViolationsByTier` | tier, event_id | Queue for manager |
| `GetViolationSummary` | event_id | Counts by status, total deductions |

---

## API Endpoints

Base path: `/api/v1/participations/{participation_id}/violations`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/` | ReportViolationAction | Required | supervisor, site_manager |
| GET | `/` | ListViolationsAction | Required | supervisor, manager, worker (own) |
| GET | `/{id}` | GetViolationAction | Required | As above |
| PUT | `/{id}` | UpdateViolationAction | Required | reporter (if pending) |
| POST | `/{id}/escalate` | EscalateViolationAction | Required | current tier manager |
| POST | `/{id}/approve` | ApproveViolationAction | Required | current tier manager |
| POST | `/{id}/reject` | RejectViolationAction | Required | current tier manager |

### Request/Response Examples

**POST /participations/{participation_id}/violations**
Request:
```json
{
    "violation_type_slug": "late_arrival",
    "description": "Arrived 30 minutes after shift start",
    "date": "2026-06-01"
}
```
Response (201):
```json
{
    "id": "violation_uuid",
    "violation_type": {
        "slug": "late_arrival",
        "name": {"ar": "تأخر", "en": "Late Arrival"},
        "default_deduction": 50.00
    },
    "current_tier": 1,
    "status": "pending",
    "message": "Violation reported. Awaiting supervisor approval."
}
```

**POST /violations/{id}/approve**
Request (optional deduction override):
```json
{
    "deduction_amount": 75.00
}
```
Response (200):
```json
{
    "id": "violation_uuid",
    "status": "approved",
    "deduction_amount": 75.00,
    "approved_by": {"id": "manager_uuid", "name": "Manager"},
    "approved_at": "2026-06-02T10:00:00Z",
    "message": "Violation approved. Deduction will be applied to payroll."
}
```

**GET /participations/{participation_id}/violations**
Response:
```json
{
    "data": [
        {
            "id": "v1",
            "violation_type": {"slug": "late_arrival", "name": "Late Arrival"},
            "date": "2026-06-01",
            "description": "Arrived 30 minutes late",
            "status": "approved",
            "deduction_amount": 50.00,
            "current_tier": 1
        },
        {
            "id": "v2",
            "violation_type": {"slug": "safety_violation", "name": "Safety Violation"},
            "date": "2026-06-02",
            "status": "pending",
            "current_tier": 2,
            "description": "Not wearing safety vest"
        }
    ],
    "summary": {
        "total_violations": 2,
        "approved_violations": 1,
        "pending_violations": 1,
        "total_deductions": 50.00
    }
}
```

---

## Presenters API Response Format

### ViolationPresenter
- id, date, description, status, current_tier
- violation_type (slug, name, default_deduction)
- reporter (id, name)
- approver (id, name) if approved
- deduction_amount (if approved)

### ViolationSummaryPresenter (for dashboard)
- total_violations, approved_count, pending_count
- total_deductions (sum of approved)

---

## Seeder Data

### ViolationSeeder
Creates sample violations:

| Worker | Violation Type | Date | Status | Tier | Deduction |
|--------|---------------|------|--------|------|-----------|
| Employee 1 | Late Arrival | June 1 | approved | 1 | 50.00 |
| Employee 2 | Safety Violation | June 2 | pending | 1 | null |
| Employee 3 | Absent without excuse | June 1 | escalated | 2 | null |
| Employee 3 | Uniform violation | June 3 | approved | 1 | 25.00 |

**Dependencies:** Requires participations and violation_types.

**Run order:** After ParticipationSeeder, ViolationTypeSeeder.

---

## Infrastructure Implementation

### Eloquent Model: ViolationModel
- Table: `participation_violations`
- Casts: `date` → date, `current_tier` → integer, `deduction_amount` → decimal, `approved_at` → datetime
- Relationships: `participation()`, `violationType()`, `reporter()`, `approver()`

### EloquentViolationRepository
Implements ViolationRepositoryInterface.

**Key methods:**
- `save()` → creates/updates violation
- `findPendingByTier()` → for manager queues
- `getTotalDeductions()` → sum aggregation for payroll

### Reflector: ViolationReflector
Converts between ViolationModel and Violation domain entity.

### Escalation Service
- Determines next tier manager based on event role assignments
- Sends notification to appropriate manager

---

## Service Provider Registration

**Class:** `Modules\ParticipationViolation\Infrastructure\Providers\ParticipationViolationServiceProvider`

**Register method:** Binds ViolationRepositoryInterface to EloquentViolationRepository

**Boot method:** Loads migrations, loads routes

**Position:** After ViolationType, before Discount.

---

## Testing Strategy

### Unit Tests
- Violation creation with valid type
- Escalation increases tier (1→2→3)
- Cannot approve beyond current tier
- Deduction defaults from violation_type

### Feature Tests
- Report violation → 201, status = pending
- Approve violation → status = approved, deduction set
- Reject violation → status = rejected
- Escalate violation → tier increases, status = escalated
- Non-supervisor cannot report → 403
- Wrong tier manager cannot approve → 403

### Integration Tests
- Violation + ViolationType: default_deduction applied
- Violation + EventRoleAssignment: escalation routes to correct manager
- Violation + Discount: approved violations trigger discounts

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Report violation | supervisor, site_manager |
| Approve/reject tier 1 | supervisor |
| Approve/reject tier 2 | area_manager |
| Approve/reject tier 3 | project_manager |
| Escalate violation | current tier manager |
| View violations | reporter, managers in chain, worker (own) |

### Validation Rules

**ReportViolation:**
- `violation_type_slug`: required, exists:violation_types
- `date`: required, date, cannot be future
- `description`: nullable, string, max:1000

**ApproveViolation:**
- `deduction_amount`: nullable, numeric, min:0
- Approver must have role for current tier
- Cannot approve already approved violation

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| ViolationReported | After report | violation_id, participation_id, type | Notify tier 1 manager |
| ViolationEscalated | Tier increased | violation_id, new_tier | Notify next tier manager |
| ViolationApproved | Status → APPROVED | violation_id, deduction_amount | Apply discount, notify payroll |
| ViolationRejected | Status → REJECTED | violation_id, reason | Notify reporter |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| VIO_001 | 404 | Violation not found | Invalid ID |
| VIO_002 | 422 | Cannot approve violation at higher tier | Approver tier < current_tier |
| VIO_003 | 409 | Violation already approved | Duplicate approval |
| VIO_004 | 422 | Invalid violation type | Type slug not found |
| VIO_005 | 403 | Insufficient tier permission | Wrong manager level |
| VIO_006 | 422 | Cannot escalate beyond tier 3 | current_tier = 3 |

---

## Performance Considerations

- **Indexes:** `(participation_id, status)`, `(status, current_tier)`, `date`
- **Composite index:** `(status, current_tier, event_id)` for manager queues
- **Caching:** Pending counts per tier cached for 5 minutes
- **Escalation:** Use queue for notifications (avoid blocking)

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| EventParticipation | `participations` table | participation_id FK |
| ViolationType | `violation_types` table, default_deduction | Type reference |
| User | `users` table | reporter, approver FK |
| EventRoleAssignment | Manager roles | Escalation authorization |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| Discount | violation_id | Apply financial penalty |
| Payroll | deduction_amount | Reduce wages |

---

## Next Steps After Building ParticipationViolation Module

### Pre-Flight Checklist
- [ ] participation_violations table migrated
- [ ] Tier escalation works (1→2→3)
- [ ] Only current tier manager can approve
- [ ] Deduction defaults from violation type
- [ ] Cannot approve already approved violation

### Immediate Next Module: Discount

**Why Discount next?**
- Discounts apply financial penalties from violations
- Links to violation_id from this module
- Also supports general event discounts

### Build Order
```
ParticipationViolation → Discount → EventTask → EventOperationalReport
```

### Commands to Run
```bash
php artisan migrate:status | grep participation_violations
php artisan module:make Discount
```

### Success Criteria
- [ ] Supervisors can report violations
- [ ] Violations escalate through manager tiers
- [ ] Approved violations trigger deductions
- [ ] Rejected violations can be re-reported
- [ ] Total deductions calculated for payroll

---

**ParticipationViolation Module Specification Complete.**   

## Notifications & Events

### Events Emitted

| Event | When | Payload | Notification Recipient |
|-------|------|---------|------------------------|
| ViolationReported | Supervisor reports violation | violation_id, participation_id, user_id, violation_type, date, reported_by | Employee (worker), tier 1 manager |
| ViolationEscalated | Manager escalates to next tier | violation_id, current_tier, new_tier, escalated_by | Next tier manager |
| ViolationApproved | Manager approves violation | violation_id, deduction_amount, approved_by | Employee, reporter (supervisor) |
| ViolationRejected | Manager rejects violation | violation_id, rejection_reason, rejected_by | Reporter (supervisor) |

### Domain Event Classes

Create these in `Domain/Events/`:

```php
// ViolationReported.php
final class ViolationReported
{
    public function __construct(
        public readonly ViolationId $violationId,
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly string $violationTypeName,
        public readonly Date $date,
        public readonly UserId $reportedBy,
        public readonly Carbon $occurredAt,
    ) {}
}

// ViolationEscalated.php
final class ViolationEscalated
{
    public function __construct(
        public readonly ViolationId $violationId,
        public readonly int $currentTier,
        public readonly int $newTier,
        public readonly UserId $escalatedBy,
        public readonly Carbon $occurredAt,
    ) {}
}

// ViolationApproved.php
final class ViolationApproved
{
    public function __construct(
        public readonly ViolationId $violationId,
        public readonly float $deductionAmount,
        public readonly UserId $approvedBy,
        public readonly Carbon $occurredAt,
    ) {}
}

// ViolationRejected.php
final class ViolationRejected
{
    public function __construct(
        public readonly ViolationId $violationId,
        public readonly string $rejectionReason,
        public readonly UserId $rejectedBy,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None. ParticipationViolation module fires events but does not listen to external events.
