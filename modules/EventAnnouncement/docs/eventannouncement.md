# EventAnnouncement Module

## Module Purpose

Manages broadcast announcements within an event. Announcements can target all workers, specific groups, or specific positions. Each announcement has a title, body (Arabic/English), and sender information. Announcements are delivered in real-time via WebSocket broadcast and also stored as notifications. This module enables mass communication from managers to workers.

---

## Table Schema

### `event_announcements`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| event_id | uuid | FK → events.id, CASCADE DELETE |
| sender_id | uuid | FK → users.id, RESTRICT |
| target_type | string | NOT NULL (all / group / position) |
| target_id | uuid | NULLABLE (group_id or position_id) |
| title | json | NOT NULL `{ar, en}` |
| body | json | NOT NULL `{ar, en}` |
| sent_at | timestamp | NOT NULL |
| created_at, updated_at | timestamps | |

CodeRabbit
Add index recommendations for query performance.

The schema documentation doesn't specify indexes beyond the primary key. Based on the repository methods (line 83) and recipient resolution logic (lines 186-189), you'll likely need indexes on frequently queried columns.

Add after line 23:

**Indexes:**
- `event_id` (for findByEvent queries)
- `sender_id` (for sender-based lookups)
- `(target_type, target_id)` composite (for findByTarget queries)
- `sent_at` (for chronological ordering)

**Target Types:**
- `all` — send to all event participants
- `group` — send to specific group (target_id = group_id)
- `position` — send to specific position (target_id = position_id)

---

## Migration Details

| File | Wave | Order |
|------|------|-------|
| `2026_03_25_111500_create_event_announcements_table.php` | Wave 7 | #40 |

**Depends on:** events, users, event_staffing_groups, event_staffing_positions

---

## Relations

- `event_announcements.event_id` → `events.id` (CASCADE DELETE)
- `event_announcements.sender_id` → `users.id` (RESTRICT)

**Polymorphic target:**
- If target_type = 'group' → target_id references `event_staffing_groups.id`
- If target_type = 'position' → target_id references `event_staffing_positions.id`
- If target_type = 'all' → target_id is NULL

code review:
CodeRabbit
Document data integrity strategy for polymorphic target references.

The polymorphic relationship (target_type + target_id) lacks database-level foreign key constraints. While this is typical for Laravel polymorphic relationships, it introduces a data integrity risk where target_id could reference non-existent groups or positions after deletion.

Consider documenting:

Application-level validation strategy to verify target existence before insert
Database triggers or check constraints (if PostgreSQL)
Cleanup strategy when groups/positions are deleted (orphaned announcements)
Add after line 50:

**Data Integrity Notes:**
- Polymorphic target_id references are NOT enforced at the database level
- Application MUST validate target existence before creating announcements
- When groups/positions are deleted, consider adding cleanup logic for orphaned announcements
- 

---

## Execution Order

**Wave 7, #40** — last table in Wave 7 (after event_expenses)

**Service Provider:** After EventExpense

---

## What's Needed From Others

| Module | What |
|--------|------|
| Event | events table |
| User | users table (sender_id) |
| EventStaffingGroup | groups table (target when type=group) |
| EventStaffingPosition | positions table (target when type=position) |
| EventParticipation | participations table (resolve recipients) |

---

## Domain Entities

**Aggregate Root:** `Announcement`

**Attributes:** AnnouncementId, EventId, SenderId, TargetType (all/group/position), TargetId (optional), Title (TranslatableText), Body (TranslatableText), SentAt (Carbon)

**Rules:** Target required for group/position; null for all; sent_at set automatically on creation

**Repository:** `AnnouncementRepositoryInterface`
- save(), findById(), findByEvent(), findByTarget(), getRecipients(announcement)

**Events:** AnnouncementBroadcast

---

## CQRS Commands

| Command | Input |
|---------|-------|
| SendAnnouncement | event_id, sender_id, target_type, target_id, title, body |
| DeleteAnnouncement | announcement_id |

| Query | Output |
|-------|--------|
| GetAnnouncement | Full announcement |
| ListAnnouncementsByEvent | Paginated announcements |
| ListAnnouncementsForUser | Announcements targeting user |

---

## API Endpoints

Base: `/api/v1/events/{event_id}/announcements`

| Method | URI | Roles |
|--------|-----|-------|
| POST | `/` | project_manager, area_manager |
| GET | `/` | project_manager, area_manager, supervisor, worker |
| GET | `/{id}` | As above |
| DELETE | `/{id}` | project_manager |
| GET | `/my-announcements` | Worker (self) |

### Request/Response Examples

**POST /events/{event_id}/announcements**
```json
{
    "target_type": "group",
    "target_id": "group_uuid",
    "title": {"ar": "اجتماع عاجل", "en": "Urgent Meeting"},
    "body": {"ar": "اجتماع الساعة 3", "en": "Meeting at 3 PM"}
}
```
Response:
```json
{
    "id": "announcement_uuid",
    "target_type": "group",
    "target": {"id": "group_uuid", "name": "Security Team"},
    "title": {"ar": "اجتماع عاجل", "en": "Urgent Meeting"},
    "body": {"ar": "اجتماع الساعة 3", "en": "Meeting at 3 PM"},
    "sent_at": "2026-06-01T10:00:00Z",
    "recipient_count": 5,
    "message": "Announcement sent to 5 recipients"
}
```

**GET /events/{event_id}/announcements**
Response:
```json
{
    "data": [
        {
            "id": "a1",
            "sender": {"id": "u1", "name": {"ar": "مدير", "en": "Manager"}},
            "target_type": "all",
            "title": {"ar": "تنبيه", "en": "Alert"},
            "body": {"ar": "تحديث الجدول", "en": "Schedule update"},
            "sent_at": "2026-06-01T09:00:00Z"
        }
    ],
    "meta": {"total": 10}
}
```

---

## Presenters

**AnnouncementPresenter:** id, title, body, target_type, target (if group/position), sender, sent_at, recipient_count

**AnnouncementSummaryPresenter:** id, title, target_type, sent_at, sender_name

---

## Seeder Data

**AnnouncementSeeder:** Sample announcements (all, group, position targets)

**Depends on:** events, users, groups, positions, participations

---

## Infrastructure

**Model:** AnnouncementModel
- Casts: title → array, body → array, sent_at → datetime

**Repository:** EloquentAnnouncementRepository

**Reflector:** AnnouncementReflector

**Recipient Resolution Service:**
- `all` → query all active participations in event
- `group` → query active participations with group_id = target_id
- `position` → query active participations with position_id = target_id

---

## Testing

**Unit:** Target type validation, recipient resolution logic

**Feature:** Send to all, send to group, send to position, list announcements

**Integration:** Announcement + Group, Announcement + Position, Announcement + Participation

---

## Security

| Action | Role |
|--------|------|
| Send announcement | project_manager, area_manager |
| View announcements | project_manager, area_manager, supervisor, worker (own event) |
| Delete announcement | project_manager |

**Validation:** target_type in: all,group,position; target_id required if group/position; title.ar/en required; body.ar/en required

---

## Error Codes

| Code | HTTP | Message |
|------|------|---------|
| ANN_001 | 404 | Announcement not found |
| ANN_002 | 422 | Invalid target type |
| ANN_003 | 422 | Target ID required for group/position |
| ANN_004 | 404 | Target group or position not found |

---

## Dependencies

**Requires:** Event, User, EventStaffingGroup, EventStaffingPosition, EventParticipation

**Provides:** Mass communication, real-time announcements

---

## Notifications & Events

### Events Emitted

| Event | When | Payload | Notification Recipient |
|-------|------|---------|------------------------|
| AnnouncementBroadcast | Announcement sent | announcement_id, event_id, target_type, target_id, title, body, sender_id | All targeted recipients |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class AnnouncementBroadcast
{
    public function __construct(
        public readonly AnnouncementId $announcementId,
        public readonly EventId $eventId,
        public readonly string $targetType,
        public readonly ?string $targetId,
        public readonly array $title,
        public readonly array $body,
        public readonly UserId $senderId,
        public readonly Carbon $sentAt,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None.

### Broadcast Channel

When `AnnouncementBroadcast` event fires, Notification module broadcasts to:

- `private-event.{eventId}` channel (all event role holders)
- `private-event.{eventId}.group.{groupId}` (if target_type = group)
- `private-user.{userId}` (individual push notifications)

---

## Next Steps

**After this module:** DigitalSignature

**Commands:**
```bash
php artisan migrate:status | grep event_announcements
php artisan module:make DigitalSignature
```

**Success:** Announcements sent to all/group/position; real-time delivery; recipient count accurate
