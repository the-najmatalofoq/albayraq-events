# EventAssetCustody Module

## Module Purpose

Manages asset custody within an event. Tracks equipment, uniforms, tools, or any physical assets handed over to workers. Each custody record links an asset (item) to a worker (participation) with handover and return tracking. Statuses: handed → returned / lost. This module ensures accountability for event assets and helps prevent loss.

---

## Table Schema

### `event_asset_custodies`

| Column                 | Type       | Constraints                            |
| ---------------------- | ---------- | -------------------------------------- |
| id                     | uuid       | PK                                     |
| event_id               | uuid       | FK → events.id, CASCADE DELETE         |
| event_participation_id | uuid       | FK → event_participations.id, RESTRICT |
| item_name              | json       | NOT NULL `{ar, en}`                    |
| description            | json       | NULLABLE `{ar, en}`                    |
| handed_at              | timestamp  | NOT NULL                               |
| returned_at            | timestamp  | NULLABLE                               |
| status                 | string     | DEFAULT: 'handed'                      |
| handed_by              | uuid       | FK → users.id, RESTRICT                |
| created_at, updated_at | timestamps |                                        |

**Status Values:** handed, returned, lost

---

## Migration Details

| File                                                       | Wave   | Order |
| ---------------------------------------------------------- | ------ | ----- |
| `2026_03_25_110500_create_event_asset_custodies_table.php` | Wave 7 | #38   |

**Depends on:** events, event_participations, users

---

## Relations

- `event_asset_custodies.event_id` → `events.id` (CASCADE DELETE)
- `event_asset_custodies.event_participation_id` → `event_participations.id` (RESTRICT)
- `event_asset_custodies.handed_by` → `users.id` (RESTRICT)

---

## Execution Order

**Wave 7, #38** — after event_operational_reports, before event_expenses

**Service Provider:** After EventOperationalReport, before EventExpense

---

## What's Needed From Others

| Module             | What                    |
| ------------------ | ----------------------- |
| Event              | events table            |
| EventParticipation | participations table    |
| User               | users table (handed_by) |

---

## Domain Entities

**Aggregate Root:** `AssetCustody`

**Attributes:** CustodyId, EventId, ParticipationId, ItemName (TranslatableText), Description (TranslatableText), HandedAt (Carbon), ReturnedAt (Carbon), Status (CustodyStatus), HandedBy (UserId)

**Status Values:** handed, returned, lost

**Rules:** Cannot return an already returned item; lost status can be set when asset not returned

**Repository:** `CustodyRepositoryInterface`

- save(), findById(), findByParticipation(), findByEvent(), findOutstandingByEvent(), returnAsset(), markLost()

**Events:** CustodyHandedOver, CustodyReturned, CustodyMarkedLost

---

## CQRS Commands

| Command         | Input                                                         |
| --------------- | ------------------------------------------------------------- |
| HandOverCustody | event_id, participation_id, item_name, description, handed_by |
| ReturnCustody   | custody_id, returned_at, returned_by                          |
| MarkLost        | custody_id, lost_by                                           |
| UpdateCustody   | custody_id, item_name, description                            |
| DeleteCustody   | custody_id                                                    |

| Query                        | Output                       |
| ---------------------------- | ---------------------------- |
| GetCustody                   | Full custody record          |
| ListCustodiesByParticipation | All assets for worker        |
| ListOutstandingByEvent       | All handed out, not returned |

---

## API Endpoints

Base: `/api/v1/events/{event_id}/custodies`

| Method | URI               | Roles                                                     |
| ------ | ----------------- | --------------------------------------------------------- |
| POST   | `/`               | site_manager, area_manager, project_manager               |
| GET    | `/`               | project_manager, area_manager, site_manager, worker (own) |
| GET    | `/{id}`           | As above                                                  |
| PUT    | `/{id}`           | site_manager, area_manager, project_manager               |
| POST   | `/{id}/return`    | site_manager, area_manager, project_manager               |
| POST   | `/{id}/mark-lost` | project_manager                                           |
| DELETE | `/{id}`           | project_manager                                           |

### Request/Response Examples

**POST /events/{event_id}/custodies**

```json
{
    "participation_id": "participation_uuid",
    "item_name": { "ar": "جهاز لاسلكي", "en": "Walkie-talkie" },
    "description": { "ar": "موديل XYZ", "en": "Model XYZ" },
    "handed_by": "user_uuid"
}
```

Response:

```json
{
    "id": "custody_uuid",
    "item_name": { "ar": "جهاز لاسلكي", "en": "Walkie-talkie" },
    "status": "handed",
    "handed_at": "2026-06-01T10:00:00Z",
    "message": "Asset handed over"
}
```

**POST /custodies/{id}/return**
Response:

```json
{
    "status": "returned",
    "returned_at": "2026-06-05T16:00:00Z",
    "message": "Asset returned"
}
```

---

## Presenters

**CustodyPresenter:** id, item_name, description, status, handed_at, returned_at, handed_by (user summary), participation (user name)

**CustodySummaryPresenter:** id, item_name, status, handed_at, worker_name

---

## Seeder Data

**CustodySeeder:** Sample assets (handed, returned, lost)

**Depends on:** events, participations, users

---

## Infrastructure

**Model:** AssetCustodyModel

- Casts: item_name → array, description → array, handed_at → datetime, returned_at → datetime

**Repository:** EloquentCustodyRepository

**Reflector:** CustodyReflector

---

## Testing

**Unit:** Status transitions, cannot return already returned

**Feature:** Hand over, return, mark lost, list outstanding

**Integration:** Custody + Participation, Custody + Event

---

## Security

| Action                 | Role                                                      |
| ---------------------- | --------------------------------------------------------- |
| Hand over/return asset | site_manager, area_manager, project_manager               |
| Mark lost              | project_manager                                           |
| View assets            | project_manager, area_manager, site_manager, worker (own) |

**Validation:** item_name.ar/en required; cannot hand over to inactive participation

---

## Error Codes

| Code    | HTTP | Message                                    |
| ------- | ---- | ------------------------------------------ |
| AST_001 | 404  | Custody record not found                   |
| AST_002 | 422  | Cannot return already returned asset       |
| AST_003 | 422  | Cannot mark lost already returned asset    |
| AST_004 | 403  | Cannot hand over to inactive participation |

---

## Dependencies

**Requires:** Event, EventParticipation, User

**Provides:** Asset tracking, accountability

---

## Notifications & Events

### Events Emitted

| Event             | When                   | Payload                                                       | Notification Recipient |
| ----------------- | ---------------------- | ------------------------------------------------------------- | ---------------------- |
| CustodyHandedOver | Asset handed to worker | custody_id, participation_id, user_id, item_name, handed_by   | Worker                 |
| CustodyReturned   | Asset returned         | custody_id, participation_id, user_id, item_name, returned_by | Handed_by (issuer)     |
| CustodyMarkedLost | Asset marked lost      | custody_id, participation_id, user_id, item_name, lost_by     | Handed_by, worker      |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class CustodyHandedOver
{
    public function __construct(
        public readonly CustodyId $custodyId,
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly string $itemName,
        public readonly UserId $handedBy,
        public readonly Carbon $handedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class CustodyReturned
{
    public function __construct(
        public readonly CustodyId $custodyId,
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly string $itemName,
        public readonly UserId $returnedBy,
        public readonly Carbon $returnedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class CustodyMarkedLost
{
    public function __construct(
        public readonly CustodyId $custodyId,
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly string $itemName,
        public readonly UserId $lostBy,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None.

---

## Next Steps

**After this module:** EventExpense

**Commands:**

```bash
php artisan migrate:status | grep event_asset_custodies
php artisan module:make EventExpense
```

**Success:** Assets tracked, handover/return flow works, lost assets flagged
