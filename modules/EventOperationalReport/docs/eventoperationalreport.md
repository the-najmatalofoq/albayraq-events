# EventOperationalReport Module

## Module Purpose

Manages operational reports for events. Reports have types (security, medical, patrol, lost_item, daily_observation, readiness), content in Arabic/English, and approval workflow. Reports are submitted by staff and approved by managers. This module is critical for Event Closure Gate 2: all operational reports must have status `approved` before an event can be closed.

---

## Table Schema

### `event_operational_reports`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| event_id | uuid | FK → events.id, CASCADE DELETE |
| report_type_id | uuid | FK → report_types.id, RESTRICT |
| author_id | uuid | FK → users.id, RESTRICT |
| title | json | NULLABLE `{ar, en}` |
| content | json | NOT NULL `{ar, en}` |
| date | date | NOT NULL |
| status | string | DEFAULT: 'draft' |
| approved_by | uuid | FK → users.id, NULLABLE |
| approved_at | timestamp | NULLABLE |
| created_at, updated_at | timestamps | |

**Status Flow:** draft → submitted → approved / rejected

---

## Migration Details

| File | Wave | Order |
|------|------|-------|
| `2026_03_25_110000_create_event_operational_reports_table.php` | Wave 7 | #37 |

**Depends on:** events, report_types, users

---

## Relations

- `event_operational_reports.event_id` → `events.id` (CASCADE DELETE)
- `event_operational_reports.report_type_id` → `report_types.id` (RESTRICT)
- `event_operational_reports.author_id` → `users.id` (RESTRICT)
- `event_operational_reports.approved_by` → `users.id` (SET NULL)

---

## Execution Order

**Wave 7, #37** — after event_tasks, before event_asset_custodies

**Service Provider:** After EventTask, before EventAssetCustody

---

## What's Needed From Others

| Module | What |
|--------|------|
| Event | events table |
| ReportType | report_types table, slug, name |
| User | users table (author_id, approved_by) |

---

## Domain Entities

**Aggregate Root:** `OperationalReport`

**Attributes:** ReportId, EventId, ReportTypeId, AuthorId, Title (TranslatableText), Content (TranslatableText), Date, Status (ReportStatus), ApprovedBy (UserId), ApprovedAt (Carbon)

**Status Values:** draft, submitted, approved, rejected

**Rules:** Only approved_by can approve; cannot modify after submission; rejected reports can be resubmitted

**Repository:** `ReportRepositoryInterface`
- save(), findById(), findByEvent(), findByStatus(), findPendingByEvent(), approve(), reject(), submit()

**Events:** ReportSubmitted, ReportApproved, ReportRejected

---

## CQRS Commands

| Command | Input |
|---------|-------|
| CreateReport | event_id, report_type_id, author_id, title, content, date |
| SubmitReport | report_id, submitted_by |
| ApproveReport | report_id, approved_by |
| RejectReport | report_id, rejected_by, rejection_reason |
| UpdateReport | report_id, title, content |
| DeleteReport | report_id |

| Query | Output |
|-------|--------|
| GetReport | Full report |
| ListReportsByEvent | Paginated reports |
| GetClosureGate2Status | event_id → boolean (all reports approved) |

---

## API Endpoints

Base: `/api/v1/events/{event_id}/reports`

| Method | URI | Roles |
|--------|-----|-------|
| POST | `/` | site_manager, area_manager, project_manager |
| GET | `/` | project_manager, area_manager, general_manager |
| GET | `/{id}` | As above + author |
| PUT | `/{id}` | Author (if draft only) |
| POST | `/{id}/submit` | Author |
| POST | `/{id}/approve` | project_manager |
| POST | `/{id}/reject` | project_manager |
| DELETE | `/{id}` | project_manager |

### Request/Response Examples

**POST /events/{event_id}/reports**
```json
{
    "report_type_slug": "daily_observation",
    "title": {"ar": "تقرير يوم 1", "en": "Day 1 Report"},
    "content": {"ar": "نص التقرير", "en": "Report content"},
    "date": "2026-06-01"
}
```
Response:
```json
{
    "id": "report_uuid",
    "status": "draft",
    "message": "Report created"
}
```

**POST /reports/{id}/submit**
Response:
```json
{
    "status": "submitted",
    "submitted_at": "2026-06-02T10:00:00Z",
    "message": "Report submitted for approval"
}
```

**GET /events/{event_id}/reports/closure-gate**
Response:
```json
{
    "event_id": "event_uuid",
    "total_reports": 5,
    "approved_reports": 4,
    "pending_reports": 1,
    "gate_passed": false,
    "message": "1 report(s) pending approval"
}
```

---

## Presenters

**ReportPresenter:** id, title, content, date, status, report_type, author, approved_by, approved_at

**ReportSummaryPresenter:** id, title, date, status, report_type_name

---

## Seeder Data

**ReportSeeder:** Sample reports for each report type (draft, submitted, approved, rejected)

**Depends on:** events, report_types, users

---

## Infrastructure

**Model:** OperationalReportModel
- Casts: title → array, content → array, date → date

**Repository:** EloquentReportRepository

**Reflector:** ReportReflector

---

## Testing

**Unit:** Status transitions (draft→submitted→approved/rejected)

**Feature:** Create report, submit, approve, reject, closure gate check

**Integration:** Report + ReportType, Report + Event (closure gate)

---

## Security

| Action | Role |
|--------|------|
| Create report | site_manager, area_manager, project_manager |
| Submit report | Author only |
| Approve/reject | project_manager only |
| View reports | project_manager, area_manager, general_manager |

**Validation:** content.ar required, content.en required, date within event range

---

## Error Codes

| Code | HTTP | Message |
|------|------|---------|
| REP_001 | 404 | Report not found |
| REP_002 | 422 | Cannot modify submitted report |
| REP_003 | 422 | Cannot approve already approved report |
| REP_004 | 403 | Only project_manager can approve |

---

## Dependencies

**Requires:** Event, ReportType, User

**Provides:** Event Closure Gate 2 validation

---

## Notifications & Events

### Events Emitted

| Event | When | Payload | Notification Recipient |
|-------|------|---------|------------------------|
| ReportSubmitted | Report submitted for approval | report_id, event_id, title, author_id, submitted_at | Project manager |
| ReportApproved | Report approved | report_id, approved_by, approved_at | Author |
| ReportRejected | Report rejected | report_id, rejected_by, rejection_reason | Author |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class ReportSubmitted
{
    public function __construct(
        public readonly ReportId $reportId,
        public readonly EventId $eventId,
        public readonly string $title,
        public readonly UserId $authorId,
        public readonly Carbon $submittedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ReportApproved
{
    public function __construct(
        public readonly ReportId $reportId,
        public readonly UserId $approvedBy,
        public readonly Carbon $approvedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class ReportRejected
{
    public function __construct(
        public readonly ReportId $reportId,
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

**After this module:** EventAssetCustody

**Commands:**
```bash
php artisan migrate:status | grep event_operational_reports
php artisan module:make EventAssetCustody
```

**Success:** Reports created, submitted, approved; closure gate detects missing approvals
