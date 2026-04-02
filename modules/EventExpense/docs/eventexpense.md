# EventExpense Module

## Module Purpose

Manages expense claims within an event. Staff can submit expenses (e.g., supplies, travel, equipment) with description, amount, and category. Expenses go through approval workflow: pending → approved / rejected. This module is critical for Event Closure Gate 3: all expenses must have status `approved` before an event can be closed. Also provides financial tracking for event budgeting.

---

## Table Schema

### `event_expenses`

| Column                 | Type          | Constraints                    |
| ---------------------- | ------------- | ------------------------------ |
| id                     | uuid          | PK                             |
| event_id               | uuid          | FK → events.id, CASCADE DELETE |
| description            | json          | NOT NULL `{ar, en}`            |
| amount                 | decimal(12,2) | NOT NULL                       |
| category               | string        | NULLABLE                       |
| status                 | string        | DEFAULT: 'pending'             |
| submitted_by           | uuid          | FK → users.id, RESTRICT        |
| approved_by            | uuid          | FK → users.id, NULLABLE        |
| approved_at            | timestamp     | NULLABLE                       |
| created_at, updated_at | timestamps    |                                |

**Status Flow:** pending → approved / rejected

---

## Migration Details

| File                                                | Wave   | Order |
| --------------------------------------------------- | ------ | ----- |
| `2026_03_25_111000_create_event_expenses_table.php` | Wave 7 | #39   |

**Depends on:** events, users

---

## Relations

- `event_expenses.event_id` → `events.id` (CASCADE DELETE)
- `event_expenses.submitted_by` → `users.id` (RESTRICT)
- `event_expenses.approved_by` → `users.id` (SET NULL)

---

## Execution Order

**Wave 7, #39** — after event_asset_custodies, before event_announcements

**Service Provider:** After EventAssetCustody, before EventAnnouncement

---

## What's Needed From Others

| Module | What                                    |
| ------ | --------------------------------------- |
| Event  | events table                            |
| User   | users table (submitted_by, approved_by) |

---

## Domain Entities

**Aggregate Root:** `Expense`

**Attributes:** ExpenseId, EventId, Description (TranslatableText), Amount (decimal), Category (optional), Status (ExpenseStatus), SubmittedBy (UserId), ApprovedBy (UserId), ApprovedAt (Carbon)

**Status Values:** pending, approved, rejected

**Rules:** Amount > 0; only pending expenses can be approved/rejected; rejected expenses can be resubmitted

**Repository:** `ExpenseRepositoryInterface`

- save(), findById(), findByEvent(), findByStatus(), getTotalApprovedByEvent(), approve(), reject()

**Events:** ExpenseSubmitted, ExpenseApproved, ExpenseRejected

---

## CQRS Commands

| Command        | Input                                                  |
| -------------- | ------------------------------------------------------ |
| SubmitExpense  | event_id, description, amount, category, submitted_by  |
| ApproveExpense | expense_id, approved_by                                |
| RejectExpense  | expense_id, rejected_by, rejection_reason              |
| UpdateExpense  | expense_id, description, amount, category (if pending) |
| DeleteExpense  | expense_id (if pending)                                |

| Query                 | Output                                     |
| --------------------- | ------------------------------------------ |
| GetExpense            | Full expense                               |
| ListExpensesByEvent   | Paginated expenses                         |
| GetClosureGate3Status | event_id → boolean (all expenses approved) |

---

## API Endpoints

Base: `/api/v1/events/{event_id}/expenses`

| Method | URI             | Roles                                                   |
| ------ | --------------- | ------------------------------------------------------- |
| POST   | `/`             | site_manager, area_manager, project_manager             |
| GET    | `/`             | project_manager, area_manager, general_manager, finance |
| GET    | `/{id}`         | As above + submitter                                    |
| PUT    | `/{id}`         | Submitter (if pending only)                             |
| POST   | `/{id}/approve` | project_manager, finance_manager                        |
| POST   | `/{id}/reject`  | project_manager, finance_manager                        |
| DELETE | `/{id}`         | project_manager (if pending)                            |
| GET    | `/closure-gate` | project_manager                                         |

### Request/Response Examples

**POST /events/{event_id}/expenses**

```json
{
    "description": { "ar": "مواد مكتبية", "en": "Office supplies" },
    "amount": 250.0,
    "category": "supplies"
}
```

Response:

```json
{
    "id": "expense_uuid",
    "description": { "ar": "مواد مكتبية", "en": "Office supplies" },
    "amount": 250.0,
    "status": "pending",
    "message": "Expense submitted for approval"
}
```

**POST /expenses/{id}/approve**
Response:

```json
{
    "status": "approved",
    "approved_at": "2026-06-02T10:00:00Z",
    "message": "Expense approved"
}
```

**GET /events/{event_id}/expenses/closure-gate**
Response:

```json
{
    "event_id": "event_uuid",
    "total_expenses": 5,
    "approved_amount": 1250.0,
    "pending_expenses": 1,
    "gate_passed": false,
    "message": "1 expense(s) pending approval"
}
```

---

## Presenters

**ExpensePresenter:** id, description, amount, category, status, submitted_by (user summary), approved_by (user summary), approved_at

**ExpenseSummaryPresenter:** id, description, amount, status, submitted_by_name

---

## Seeder Data

**ExpenseSeeder:** Sample expenses (pending, approved, rejected)

**Depends on:** events, users

---

## Infrastructure

**Model:** ExpenseModel

- Casts: description → array, amount → decimal

**Repository:** EloquentExpenseRepository

**Reflector:** ExpenseReflector

---

## Testing

**Unit:** Amount validation, status transitions

**Feature:** Submit, approve, reject, closure gate check

**Integration:** Expense + Event (closure gate)

---

## Security

| Action         | Role                                                               |
| -------------- | ------------------------------------------------------------------ |
| Submit expense | site_manager, area_manager, project_manager                        |
| Approve/reject | project_manager, finance_manager                                   |
| View expenses  | project_manager, area_manager, general_manager, finance, submitter |

**Validation:** amount > 0; description.ar/en required

---

## Error Codes

| Code    | HTTP | Message                                 |
| ------- | ---- | --------------------------------------- |
| EXP_001 | 404  | Expense not found                       |
| EXP_002 | 422  | Cannot approve already approved expense |
| EXP_003 | 422  | Cannot modify approved expense          |
| EXP_004 | 422  | Amount must be positive                 |
| EXP_005 | 403  | Insufficient permission to approve      |

---

## Dependencies

**Requires:** Event, User

**Provides:** Event Closure Gate 3 validation

---

## Notifications & Events

### Events Emitted

| Event            | When              | Payload                                              | Notification Recipient           |
| ---------------- | ----------------- | ---------------------------------------------------- | -------------------------------- |
| ExpenseSubmitted | Expense submitted | expense_id, event_id, amount, category, submitted_by | Project manager, finance manager |
| ExpenseApproved  | Expense approved  | expense_id, approved_by, approved_at                 | Submitter                        |
| ExpenseRejected  | Expense rejected  | expense_id, rejected_by, rejection_reason            | Submitter                        |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class ExpenseSubmitted
{
    public function __construct(
        public readonly ExpenseId $expenseId,
        public readonly EventId $eventId,
        public readonly float $amount,
        public readonly ?string $category,
        public readonly UserId $submittedBy,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ExpenseApproved
{
    public function __construct(
        public readonly ExpenseId $expenseId,
        public readonly UserId $approvedBy,
        public readonly Carbon $approvedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ExpenseRejected
{
    public function __construct(
        public readonly ExpenseId $expenseId,
        public readonly UserId $rejectedBy,
        public readonly string $rejectionReason,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None.

---

## Next Steps

**After this module:** EventAnnouncement

**Commands:**

```bash
php artisan migrate:status | grep event_expenses
php artisan module:make EventAnnouncement
```

**Success:** Expenses submitted, approved; closure gate detects pending approvals
