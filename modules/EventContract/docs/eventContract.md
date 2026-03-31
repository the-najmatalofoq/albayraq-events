# EventContract Module

## Module Purpose

The EventContract module manages the employment agreement between a worker (participation) and the event organizer. Every active participation requires a signed contract before the worker can clock attendance, receive evaluations, or get paid. Contracts go through a mandatory 5-step acceptance process: read contract, read regulations, read guide, watch video, pass quiz. The module tracks contract status, wage details, rejection reasons, and digital signatures.

This module is critical for legal compliance and payroll processing.

---

## Table Schema

### `event_contracts`

| Column                 | Type          | Constraints                                                   | Description                                                           |
| ---------------------- | ------------- | ------------------------------------------------------------- | --------------------------------------------------------------------- |
| id                     | uuid          | PRIMARY KEY                                                   | Auto-generated UUID                                                   |
| event_participation_id | uuid          | FOREIGN KEY → event_participations.id, UNIQUE, CASCADE DELETE | Links to participation                                                |
| contract_type          | string        | NOT NULL                                                      | daily or monthly                                                      |
| wage_amount            | decimal(10,2) | NOT NULL                                                      | Contracted wage amount                                                |
| terms                  | json          | NULLABLE                                                      | Custom contract terms in Arabic/English                               |
| status                 | string        | DEFAULT: 'pending'                                            | pending, accepted, rejected_by_employee, rejected_by_admin, cancelled |
| rejection_reason_id    | uuid          | FOREIGN KEY → contract_rejection_reasons.id, NULLABLE         | Why rejected (if applicable)                                          |
| rejection_notes        | text          | NULLABLE                                                      | Additional rejection details                                          |
| digital_signature_id   | uuid          | FOREIGN KEY → digital_signatures.id, NULLABLE                 | Signature on accepted contract                                        |
| accepted_at            | timestamp     | NULLABLE                                                      | When contract was accepted                                            |
| rejected_at            | timestamp     | NULLABLE                                                      | When contract was rejected                                            |
| sent_at                | timestamp     | NULLABLE                                                      | When contract was sent to worker                                      |
| created_at             | timestamp     | NOT NULL                                                      |                                                                       |
| updated_at             | timestamp     | NOT NULL                                                      |                                                                       |

**Unique Constraint:** `event_participation_id` — one contract per participation.

**Status Flow:**

```
pending → accepted (all 5 steps complete)
pending → rejected_by_employee (worker rejects)
pending → rejected_by_admin (admin rejects)
pending → cancelled (event cancelled, position removed)
accepted → (terminal state, cannot change)
rejected_by_employee → pending (if worker changes mind, admin resets)
```

### `contract_acceptance_steps`

| Column           | Type      | Constraints                                      | Description                                                         |
| ---------------- | --------- | ------------------------------------------------ | ------------------------------------------------------------------- |
| id               | uuid      | PRIMARY KEY                                      |                                                                     |
| contract_id      | uuid      | FOREIGN KEY → event_contracts.id, CASCADE DELETE | Parent contract                                                     |
| step             | string    | NOT NULL                                         | read_contract, read_regulations, read_guide, watch_video, pass_quiz |
| is_completed     | boolean   | DEFAULT: false                                   | Step completed flag                                                 |
| completed_at     | timestamp | NULLABLE                                         | When step was completed                                             |
| duration_seconds | integer   | NULLABLE                                         | Time spent on step (for analytics)                                  |
| metadata         | json      | NULLABLE                                         | Step-specific data (quiz score, video watch percentage)             |
| created_at       | timestamp | NOT NULL                                         |                                                                     |
| updated_at       | timestamp | NOT NULL                                         |                                                                     |

**Unique Constraint:** `(contract_id, step)` — each step can be completed only once.

---

## Migration Details

| Migration File                                                 | Wave   | Order | Dependencies                                                         |
| -------------------------------------------------------------- | ------ | ----- | -------------------------------------------------------------------- |
| `2026_03_25_106000_create_event_contracts_table.php`           | Wave 6 | #23   | event_participations, contract_rejection_reasons, digital_signatures |
| `2026_03_25_106500_create_contract_acceptance_steps_table.php` | Wave 6 | #24   | event_contracts                                                      |

**Position:** Wave 6 — immediately after digital_signatures, before attendance.

---

## Relations

### Foreign Keys

- `event_contracts.event_participation_id` → `event_participations.id` (CASCADE DELETE)
- `event_contracts.rejection_reason_id` → `contract_rejection_reasons.id` (SET NULL)
- `event_contracts.digital_signature_id` → `digital_signatures.id` (SET NULL)
- `contract_acceptance_steps.contract_id` → `event_contracts.id` (CASCADE DELETE)

### Eloquent Relationships

```php
// ContractModel
public function participation(): BelongsTo  // EventParticipation
public function rejectionReason(): BelongsTo  // ContractRejectionReason
public function digitalSignature(): BelongsTo  // DigitalSignature
public function acceptanceSteps(): HasMany  // ContractAcceptanceStep

// ContractAcceptanceStepModel
public function contract(): BelongsTo  // EventContract
```

---

## Execution Order

**Build Sequence Position:** Wave 6, #23 — after event_participations and digital_signatures.

```
Wave 5:
  #21: event_participations

Wave 6:
  #22: digital_signatures
  #23: event_contracts ← YOU ARE HERE
  #24: contract_acceptance_steps
  #25-34: other Wave 6 tables
```

**Service Provider Registration:** After EventParticipation, before EventAttendance.

```php
return [
    Modules\EventParticipation\Infrastructure\Providers\EventParticipationServiceProvider::class,
    Modules\EventContract\Infrastructure\Providers\EventContractServiceProvider::class, // HERE
    Modules\EventAttendance\Infrastructure\Providers\EventAttendanceServiceProvider::class,
];
```

---

## What's Needed From Others

### Required Modules (All Must Exist)

| Module                  | What                                               | Why                               |
| ----------------------- | -------------------------------------------------- | --------------------------------- |
| EventParticipation      | `event_participations` table, Participation entity | contract belongs to participation |
| ContractRejectionReason | `contract_rejection_reasons` table                 | rejection_reason_id foreign key   |
| DigitalSignature        | `digital_signatures` table                         | digital_signature_id foreign key  |
| Quiz                    | Quiz data                                          | pass_quiz step references quiz    |
| User                    | User entity                                        | Who accepted/signed               |

### What Contract Module Provides to Others

| Recipient                  | What            | Purpose                                      |
| -------------------------- | --------------- | -------------------------------------------- |
| EventAttendance            | Contract status | Only accepted contracts can clock attendance |
| Payroll module             | Wage data       | Calculate worker pay                         |
| EventParticipationBadge    | Contract status | Badges only for accepted contracts           |
| EventExperienceCertificate | Contract status | Certificates only for accepted contracts     |

---

## Domain Entities

### Aggregate Root: `Contract`

**Identity:** ContractId (UUID)

**Core Attributes:**

- **ParticipationId:** Reference to participation
- **ContractType:** Enum (daily, monthly)
- **WageAmount:** Decimal
- **Terms:** TranslatableText — custom terms
- **Status:** ContractStatus enum
- **RejectionReason:** Optional (if rejected)
- **RejectionNotes:** Optional text
- **DigitalSignatureId:** Optional (if accepted)
- **AcceptedAt:** Optional timestamp
- **RejectedAt:** Optional timestamp
- **SentAt:** Timestamp (when first sent)

**Business Rules:**

- Contract automatically created when participation is created
- Contract must be accepted before first attendance
- All 5 acceptance steps must be completed before acceptance
- Cannot modify wage after acceptance
- Rejected contracts can be resent (admin action)
- Cancelled contracts cannot be reactivated

### Value Objects

- **ContractId:** UUID wrapper
- **ContractTypeEnum:** DAILY, MONTHLY
- **ContractStatusEnum:** PENDING, ACCEPTED, REJECTED_BY_EMPLOYEE, REJECTED_BY_ADMIN, CANCELLED
- **AcceptanceStep:** Enum of 5 steps

### Acceptance Steps (Order enforced)

1. **read_contract** — Worker reads the contract document
2. **read_regulations** — Worker reads event regulations
3. **read_guide** — Worker reads worker guide
4. **watch_video** — Worker watches training video
5. **pass_quiz** — Worker passes comprehension quiz (score >= passing_score)

### Repository Interface: `ContractRepositoryInterface`

- `save(Contract $contract): void`
- `findById(ContractId $id): ?Contract`
- `findByParticipation(ParticipationId $participationId): ?Contract`
- `findByStatus(ContractStatus $status): array`
- `findPendingByEvent(EventId $eventId): array`
- `delete(ContractId $id): void`
- `updateStatus(ContractId $id, ContractStatus $status): void`

### Domain Events

- `ContractCreated` — When contract is created (participation created)
- `ContractSent` — When contract is sent to worker
- `ContractStepCompleted` — When an acceptance step is completed
- `ContractAccepted` — When all 5 steps done, status → ACCEPTED
- `ContractRejected` — When worker or admin rejects
- `ContractCancelled` — When contract is cancelled

---

## CQRS Commands

### Commands (Write)

| Command                    | Input                                                | Behavior                                                                        |
| -------------------------- | ---------------------------------------------------- | ------------------------------------------------------------------------------- |
| `CreateContract`           | participation_id, contract_type, wage_amount, terms  | Creates contract with PENDING status (auto-triggered on participation creation) |
| `SendContract`             | contract_id                                          | Sets sent_at timestamp, sends notification to worker                            |
| `CompleteStep`             | contract_id, step, metadata (duration, score)        | Marks step completed, checks if all 5 done                                      |
| `AcceptContract`           | contract_id, digital_signature_id, accepted_by       | Changes status to ACCEPTED, sets accepted_at                                    |
| `RejectContractByEmployee` | contract_id, rejection_reason_id, notes              | Changes status to REJECTED_BY_EMPLOYEE                                          |
| `RejectContractByAdmin`    | contract_id, rejection_reason_id, notes, rejected_by | Changes status to REJECTED_BY_ADMIN                                             |
| `CancelContract`           | contract_id, cancelled_by, reason                    | Changes status to CANCELLED                                                     |
| `ResendContract`           | contract_id                                          | Resets steps, changes status back to PENDING                                    |
| `UpdateWage`               | contract_id, new_wage_amount, updated_by             | Updates wage (only if not accepted yet)                                         |

### Queries (Read)

| Query                        | Input             | Output                                        |
| ---------------------------- | ----------------- | --------------------------------------------- |
| `GetContract`                | contract_id       | Full contract with steps, participation, user |
| `ListContractsByEvent`       | event_id, filters | Paginated contracts                           |
| `GetContractProgress`        | contract_id       | Which steps completed, which pending          |
| `CheckAcceptanceEligibility` | contract_id       | Boolean (all steps done?)                     |

---

## API Endpoints

Base path: `/api/v1/participations/{participation_id}/contract`

| Method | URI                      | Action                 | Auth     | Roles Allowed                                  |
| ------ | ------------------------ | ---------------------- | -------- | ---------------------------------------------- |
| GET    | `/`                      | GetContractAction      | Required | Worker (own), project_manager, general_manager |
| POST   | `/send`                  | SendContractAction     | Required | project_manager                                |
| POST   | `/steps/{step}/complete` | CompleteStepAction     | Required | Worker (own)                                   |
| POST   | `/accept`                | AcceptContractAction   | Required | Worker (own)                                   |
| POST   | `/reject/employee`       | RejectByEmployeeAction | Required | Worker (own)                                   |
| POST   | `/reject/admin`          | RejectByAdminAction    | Required | project_manager                                |
| POST   | `/cancel`                | CancelContractAction   | Required | project_manager, system_controller             |
| POST   | `/resend`                | ResendContractAction   | Required | project_manager                                |
| PUT    | `/wage`                  | UpdateWageAction       | Required | project_manager                                |

### Request/Response Examples

**GET /participations/{participation_id}/contract**
Response:

```json
{
    "id": "contract_uuid",
    "participation_id": "participation_uuid",
    "contract_type": "daily",
    "wage_amount": 150.0,
    "terms": { "ar": "الشروط", "en": "Terms" },
    "status": "pending",
    "sent_at": "2026-06-01T08:00:00Z",
    "steps": [
        {
            "step": "read_contract",
            "is_completed": true,
            "completed_at": "2026-06-01T08:05:00Z"
        },
        {
            "step": "read_regulations",
            "is_completed": true,
            "completed_at": "2026-06-01T08:10:00Z"
        },
        { "step": "read_guide", "is_completed": false, "completed_at": null },
        { "step": "watch_video", "is_completed": false, "completed_at": null },
        { "step": "pass_quiz", "is_completed": false, "completed_at": null }
    ],
    "acceptance_ready": false
}
```

**POST /steps/read_contract/complete**
Request:

```json
{
    "duration_seconds": 300
}
```

Response:

```json
{
    "step": "read_contract",
    "is_completed": true,
    "steps_remaining": 4,
    "acceptance_ready": false
}
```

**POST /accept**
Request:

```json
{
    "digital_signature_id": "signature_uuid"
}
```

Response (200):

```json
{
    "status": "accepted",
    "accepted_at": "2026-06-01T09:00:00Z",
    "message": "Contract accepted. You can now clock attendance."
}
```

---

## Presenters API Response Format

### ContractPresenter

- Embeds participation summary (user name, event name, position)
- Lists all 5 steps with completion status
- Calculates `acceptance_ready` boolean
- Includes wage and contract type

### StepPresenter

- Step name, completion status, completed_at timestamp
- Duration in seconds (if completed)
- Metadata (quiz score if step = pass_quiz)

---

## Seeder Data

### ContractSeeder

Creates sample contracts for existing participations:

| Participation               | Contract Type | Wage    | Status               | Steps Completed |
| --------------------------- | ------------- | ------- | -------------------- | --------------- |
| Employee 1 @ Tech Conf      | daily         | 150.00  | accepted             | all 5           |
| Employee 2 @ Tech Conf      | daily         | 120.00  | pending              | 2 of 5          |
| Employee 3 @ Tech Conf      | monthly       | 4000.00 | pending              | 0 of 5          |
| Employee 4 @ Marketing Expo | daily         | 100.00  | rejected_by_employee | 1 of 5          |

**Dependencies:** Requires participations and rejection reasons.

**Run order:** After ParticipationSeeder, before AttendanceSeeder.

---

## Infrastructure Implementation

### Eloquent Models

**ContractModel:**

- Table: `event_contracts`
- Casts: `terms` → array, `wage_amount` → decimal, `accepted_at` → datetime, `rejected_at` → datetime, `sent_at` → datetime
- Relationships: `participation()`, `rejectionReason()`, `digitalSignature()`, `acceptanceSteps()`

**ContractAcceptanceStepModel:**

- Table: `contract_acceptance_steps`
- Casts: `is_completed` → boolean, `completed_at` → datetime, `metadata` → array
- Relationships: `contract()`

### EloquentContractRepository

Implements ContractRepositoryInterface.

**Key methods:**

- `save()` → creates or updates ContractModel and its steps
- `findByParticipation()` → unique contract per participation
- `updateStatus()` → changes status, sets timestamps accordingly

### Reflector: ContractReflector

Converts between ContractModel and Contract domain entity:

- Model → Domain: reconstructs with ParticipationId, steps collection
- Domain → Model: maps attributes, handles steps as array

---

## Service Provider Registration

**Class:** `Modules\EventContract\Infrastructure\Providers\EventContractServiceProvider`

**Register method:** Binds ContractRepositoryInterface to EloquentContractRepository

**Boot method:** Loads migrations, loads routes, listens to ParticipationCreated event to auto-create contract

**Position:** After EventParticipationServiceProvider, before EventAttendanceServiceProvider.

---

## Testing Strategy

### Unit Tests

- Contract creation with valid/invalid wage
- 5-step acceptance flow (must complete in order)
- Cannot accept without all steps
- Status transitions validation

### Feature Tests

- Contract auto-created on participation creation → 201
- Complete step → step marked completed
- Accept contract → status = accepted, only after all steps
- Reject contract → status = rejected_by_employee
- Update wage before acceptance → 200
- Update wage after acceptance → 422
- Non-owner cannot complete steps → 403

### Integration Tests

- Contract + Participation: contract belongs to participation
- Contract + Quiz: pass_quiz step validates quiz score
- Contract + Attendance: only accepted contracts can clock attendance

---

## Security and Validation Rules

### Authorization Rules

| Action            | Required Role                                  |
| ----------------- | ---------------------------------------------- |
| View contract     | Worker (own), project_manager, general_manager |
| Complete steps    | Worker (own) only                              |
| Accept contract   | Worker (own) only                              |
| Reject (employee) | Worker (own) only                              |
| Reject (admin)    | project_manager                                |
| Send contract     | project_manager                                |
| Cancel/resend     | project_manager, system_controller             |
| Update wage       | project_manager (before acceptance only)       |

### Validation Rules

**CompleteStep:**

- `step`: required, in: read_contract, read_regulations, read_guide, watch_video, pass_quiz
- Steps must be completed in order (cannot complete step 3 before step 2)
- `pass_quiz` requires quiz score >= passing_score (validated against Quiz module)

**AcceptContract:**

- `digital_signature_id`: required, exists:digital_signatures
- All 5 steps must be completed
- Contract status must be PENDING

---

## Events Emitted

| Event                 | When                        | Payload                                | Listeners                         |
| --------------------- | --------------------------- | -------------------------------------- | --------------------------------- |
| ContractCreated       | After participation created | contract_id, participation_id          | Queue for sending                 |
| ContractSent          | After send action           | contract_id, sent_at                   | Notify worker via SMS/email       |
| ContractStepCompleted | Step marked done            | contract_id, step, metadata            | Check if all steps done           |
| ContractAccepted      | Status → ACCEPTED           | contract_id, accepted_at, signature_id | Enable attendance, notify payroll |
| ContractRejected      | Status → REJECTED           | contract_id, reason, notes             | Notify project_manager            |
| ContractCancelled     | Status → CANCELLED          | contract_id, reason                    | Remove from active workers        |

---

## Error Handling

| Code    | HTTP | Message                                            | When                  |
| ------- | ---- | -------------------------------------------------- | --------------------- |
| CTR_001 | 404  | Contract not found                                 | Invalid contract ID   |
| CTR_002 | 422  | Steps must be completed in order                   | Skipped step          |
| CTR_003 | 422  | Cannot accept contract before completing all steps | Missing steps         |
| CTR_004 | 422  | Cannot modify wage after acceptance                | Update after ACCEPTED |
| CTR_005 | 409  | Contract already accepted                          | Duplicate accept      |
| CTR_006 | 422  | Quiz score below passing threshold                 | pass_quiz step fails  |
| CTR_007 | 403  | Contract already sent                              | Duplicate send        |
| CTR_008 | 422  | Invalid contract type                              | Not daily or monthly  |

---

## Performance Considerations

- **Indexes:** `event_contracts.event_participation_id` (unique), `status`, `accepted_at`
- **Composite index:** `(status, accepted_at)` for reporting
- **Eager loading:** Always load `acceptanceSteps` when displaying contract progress
- **Caching:** Contract status cached for 5 minutes (invalidated on any step completion)
- **Batch operations:** Use `updateStatus()` for mass contract updates (e.g., event closure)

---

## Dependencies

### Required From Other Modules

| Module                  | What                               | Why                               |
| ----------------------- | ---------------------------------- | --------------------------------- |
| EventParticipation      | `event_participations` table       | contract belongs to participation |
| ContractRejectionReason | `contract_rejection_reasons` table | rejection_reason_id FK            |
| DigitalSignature        | `digital_signatures` table         | signature_id FK                   |
| Quiz                    | Quiz passing score                 | pass_quiz validation              |

### Provided To Other Modules

| Recipient                  | What            | Purpose                        |
| -------------------------- | --------------- | ------------------------------ |
| EventAttendance            | Contract status | Gate attendance recording      |
| Payroll                    | Wage data       | Calculate payments             |
| ParticipationEvaluation    | Contract status | Only evaluate accepted workers |
| EventParticipationBadge    | Contract status | Badges only for accepted       |
| EventExperienceCertificate | Contract status | Certificates only for accepted |

---

## Next Steps After Building EventContract Module

### Pre-Flight Checklist

- [ ] event_contracts table migrated
- [ ] contract_acceptance_steps table migrated
- [ ] Contract auto-creation on participation creation works
- [ ] 5-step acceptance flow functional
- [ ] Accept contract requires digital signature
- [ ] Quiz step validates passing score
- [ ] Rejection reasons work
- [ ] Contract status gates attendance

### Immediate Next Module: ContractAcceptanceStep

**Note:** ContractAcceptanceStep is part of this module (table already created). No separate module needed. The next true module is **EventAttendance**.

**Build Order after Contract:**

```
EventParticipation → EventContract → EventAttendance → ParticipationEvaluation → ParticipationViolation
```

### Integration Point to Test

After EventAttendance is built, test:

1. Contract status = pending → attendance check-in blocked
2. Contract status = accepted → attendance check-in allowed
3. Attendance records reference participation

### Commands to Run

```bash
# Verify Contract module
php artisan migrate:status | grep event_contracts
php artisan tinker --execute="Modules\EventContract\Domain\Contract::first()"

# Create EventAttendance module
php artisan module:make EventAttendance

# Register provider after ContractServiceProvider
```

### Success Criteria

- [ ] Contracts auto-created for all participations
- [ ] 5-step acceptance workflow enforced
- [ ] Digital signature required for acceptance
- [ ] Quiz passing score validated
- [ ] Contract status gates attendance
- [ ] Rejection reasons tracked
- [ ] Wage locked after acceptance

---

**EventContract Module Specification Complete.**

