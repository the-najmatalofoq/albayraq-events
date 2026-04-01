# Discount Module

## Module Purpose

Manages financial deductions applied to worker wages. Discounts originate from approved violations or can be manually created by managers. Each discount has a type (fixed amount or percentage), rate, risk level, and optional link to a specific violation. Multiple discounts can apply to a worker, all deducted from final payroll.

---

## Table Schema

### `discounts`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| event_id | uuid | FK → events.id, CASCADE DELETE |
| violation_id | uuid | FK → participation_violations.id, NULLABLE, UNIQUE |
| name | json | NULLABLE |
| discount_type | string | NOT NULL (fixed / percentage) |
| rate | decimal(10,2) | NOT NULL |
| risk_level | string | NULLABLE (low / medium / high) |
| created_at, updated_at | timestamps | |

**Unique Constraint:** `violation_id` — one discount per violation

---

## Migration Details

| File | Wave | Order |
|------|------|-------|
| `2026_03_25_108500_create_discounts_table.php` | Wave 6 | #32 |

**Depends on:** events, participation_violations

---

## Relations

- `discounts.event_id` → `events.id` (CASCADE DELETE)
- `discounts.violation_id` → `participation_violations.id` (SET NULL, UNIQUE)

---

## Execution Order

**Wave 6, #32** — after participation_violations, before badges

**Service Provider:** After ParticipationViolation, before EventParticipationBadge

---

## What's Needed From Others

| Module | What |
|--------|------|
| Event | events table |
| ParticipationViolation | violation_id FK |

---

## Domain Entities

**Aggregate Root:** `Discount`

**Attributes:** DiscountId, EventId, ViolationId (optional), Name (TranslatableText), Type (fixed/percentage), Rate (decimal), RiskLevel (low/medium/high)

**Rules:** Rate must be > 0; fixed type = absolute amount; percentage type = % of wage; violation_id optional (manual discounts); one discount per violation

**Repository:** `DiscountRepositoryInterface`
- save(), findById(), findByEvent(), findByViolation(), findByParticipation (via violation), getTotalDiscountsForParticipation(), delete()

**Events:** DiscountCreated, DiscountApplied (to payroll)

---

## CQRS Commands

| Command | Input |
|---------|-------|
| CreateDiscountFromViolation | violation_id, event_id (auto-triggered on violation approval) |
| CreateManualDiscount | event_id, name, discount_type, rate, risk_level, created_by |
| UpdateDiscount | discount_id, name, rate, risk_level |
| DeleteDiscount | discount_id |

| Query | Output |
|-------|--------|
| GetDiscount | Full discount |
| ListDiscountsByEvent | All for event |
| GetDiscountsForParticipation | All applicable to worker |

---

## API Endpoints

Base: `/api/v1/events/{event_id}/discounts`

| Method | URI | Roles |
|--------|-----|-------|
| POST | `/` | project_manager, general_manager |
| GET | `/` | project_manager, area_manager, general_manager |
| GET | `/{id}` | As above |
| PUT | `/{id}` | project_manager |
| DELETE | `/{id}` | project_manager, system_controller |
| GET | `/participations/{participation_id}` | As above + worker (own) |

### Request/Response Examples

**POST /events/{event_id}/discounts**
```json
{
    "name": {"ar": "خصم تأخير", "en": "Late Penalty"},
    "discount_type": "fixed",
    "rate": 50.00,
    "risk_level": "low"
}
```
Response:
```json
{
    "id": "discount_uuid",
    "name": {"ar": "خصم تأخير", "en": "Late Penalty"},
    "discount_type": "fixed",
    "rate": 50.00,
    "risk_level": "low"
}
```

**GET /discounts/participations/{participation_id}**
Response:
```json
{
    "participation_id": "p_uuid",
    "total_deductions": 125.00,
    "discounts": [
        {
            "id": "d1",
            "name": "Late Arrival",
            "discount_type": "fixed",
            "rate": 50.00,
            "source": "violation",
            "violation_id": "v1"
        },
        {
            "id": "d2",
            "name": "Safety Violation",
            "discount_type": "percentage",
            "rate": 15.00,
            "source": "violation",
            "violation_id": "v2"
        }
    ]
}
```

---

## Presenters

**DiscountPresenter:** id, name (ar/en), discount_type, rate, risk_level, source (violation/manual)

**ParticipationDiscountPresenter:** total_deductions, discounts array

---

## Seeder Data

**DiscountSeeder:** Sample discounts (fixed and percentage)

**Depends on:** events, violations

---

## Infrastructure

**Model:** DiscountModel (casts: name→array, rate→decimal)

**Repository:** EloquentDiscountRepository

**Reflector:** DiscountReflector

**Service:** DiscountCalculationService (calculates total per participation)

---

## Testing

**Unit:** Fixed vs percentage calculation, rate validation

**Feature:** Create manual discount, auto-create from violation, list by event, get per participation

**Integration:** Discount + Violation (one-to-one), Discount + Payroll

---

## Security

| Action | Role |
|--------|------|
| Create manual discount | project_manager, general_manager |
| Update/delete | project_manager |
| View | project_manager, area_manager, general_manager |
| View own deductions | Worker (self) |

**Validation:** discount_type in: fixed,percentage; rate > 0; risk_level in: low,medium,high

---

## Events

| Event | Payload |
|-------|---------|
| DiscountCreated | discount_id, event_id, violation_id (if any) |
| DiscountApplied | discount_id, participation_id, amount |

---

## Error Codes

| Code | HTTP | Message |
|------|------|---------|
| DISC_001 | 404 | Discount not found |
| DISC_002 | 422 | Invalid discount type |
| DISC_003 | 422 | Rate must be positive |
| DISC_004 | 409 | Discount already exists for this violation |

---

## Dependencies

**Requires:** Event, ParticipationViolation

**Provides:** Payroll (deduction calculations)

---

## Next Steps

**After this module:** EventParticipationBadge

**Commands:**
```bash
php artisan migrate:status | grep discounts
php artisan module:make EventParticipationBadge
```

**Success:** Discounts auto-create from violations, manual discounts work, total deductions calculate correctly
```

