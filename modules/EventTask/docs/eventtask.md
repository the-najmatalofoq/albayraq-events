# EventTask Module

## Module Purpose

Manages tasks assigned to workers or groups within an event. Tasks have titles, descriptions, due dates, optional locations, and status flow: pending → in_progress → completed / overdue. Tasks can be assigned to individual users or entire groups. This module enables operational task management and worker accountability.

---

## Table Schema

### `event_tasks`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| event_id | uuid | FK → events.id, CASCADE DELETE |
| assigned_to | uuid | FK → users.id, RESTRICT |
| group_id | uuid | FK → event_staffing_groups.id, NULLABLE |
| title | json | NOT NULL `{ar, en}` |
| description | json | NULLABLE `{ar, en}` |
| due_at | timestamp | NULLABLE |
| location_latitude | decimal(10,7) | NULLABLE |
| location_longitude | decimal(10,7) | NULLABLE |
| status | string | DEFAULT: 'pending' |
| created_by | uuid | FK → users.id, RESTRICT |
| created_at, updated_at | timestamps | |

**Status Flow:** pending → in_progress → completed / overdue

### `task_schedules`

| Column | Type | Constraints |
|--------|------|-------------|
| id | uuid | PK |
| task_id | uuid | FK → event_tasks.id, CASCADE DELETE |
| date | date | NOT NULL |
| start_time | time | NULLABLE |
| end_time | time | NULLABLE |
| created_at, updated_at | timestamps | |

---

## Migration Details

| File | Wave | Order |
|------|------|-------|
| `2026_03_25_109500_create_event_tasks_table.php` | Wave 7 | #35 |
| `2026_03_25_109600_create_task_schedules_table.php` | Wave 7 | #36 |

**Depends on:** events, users, event_staffing_groups

---

## Relations

- `event_tasks.event_id` → `events.id` (CASCADE DELETE)
- `event_tasks.assigned_to` → `users.id` (RESTRICT)
- `event_tasks.group_id` → `event_staffing_groups.id` (SET NULL)
- `event_tasks.created_by` → `users.id` (RESTRICT)
- `task_schedules.task_id` → `event_tasks.id` (CASCADE DELETE)

---

## Execution Order

**Wave 7, #35-36** — after event_participations, before event_operational_reports

**Service Provider:** After EventParticipation, before EventOperationalReport

---

## What's Needed From Others

| Module | What |
|--------|------|
| Event | events table |
| User | users table (assigned_to, created_by) |
| EventStaffingGroup | groups table (group_id) |

---

## Domain Entities

**Aggregate Root:** `Task`

**Attributes:** TaskId, EventId, AssignedTo (UserId), GroupId (optional), Title (TranslatableText), Description (TranslatableText), DueAt (Carbon), Location (optional), Status (TaskStatus), CreatedBy (UserId)

**Status Values:** pending, in_progress, completed, overdue

**Rules:** Can only assign to user OR group, not both; overdue auto-set by scheduled job

**Repository:** `TaskRepositoryInterface`
- save(), findById(), findByEvent(), findByAssignedUser(), findOverdue(), updateStatus()

---

## CQRS Commands

| Command | Input |
|---------|-------|
| CreateTask | event_id, assigned_to, group_id, title, description, due_at, location, created_by |
| UpdateTask | task_id, title, description, due_at, location |
| StartTask | task_id, started_by |
| CompleteTask | task_id, completed_by |
| DeleteTask | task_id |
| AssignTask | task_id, assigned_to |

| Query | Output |
|-------|--------|
| GetTask | Full task with schedules |
| ListTasksByEvent | Paginated tasks |
| ListMyTasks | Tasks assigned to current user |

---

## API Endpoints

Base: `/api/v1/events/{event_id}/tasks`

| Method | URI | Roles |
|--------|-----|-------|
| POST | `/` | project_manager, area_manager, site_manager |
| GET | `/` | project_manager, area_manager, site_manager, assigned user |
| GET | `/{id}` | As above |
| PUT | `/{id}` | project_manager |
| POST | `/{id}/start` | Assigned user |
| POST | `/{id}/complete` | Assigned user |
| DELETE | `/{id}` | project_manager |

---

## Presenters

**TaskPresenter:** id, title, description, due_at, status, location, assigned_to (user summary), created_by

**TaskSummaryPresenter:** id, title, status, due_at, assigned_to name

---

## Seeder Data

**TaskSeeder:** Sample tasks for events (pending, in_progress, completed, overdue)

**Depends on:** events, users, groups

---

## Infrastructure

**Models:** TaskModel, TaskScheduleModel

**Repository:** EloquentTaskRepository

**Reflector:** TaskReflector

**Scheduled Job:** `tasks:mark-overdue` runs daily at midnight

---

## Testing

**Unit:** Status transitions, due date validation, assignment rules

**Feature:** Create task, start task, complete task, overdue auto-mark

**Integration:** Task + User, Task + Group

---

## Security

| Action | Role |
|--------|------|
| Create/update task | project_manager, area_manager, site_manager |
| Start/complete task | Assigned user only |
| Delete task | project_manager |

**Validation:** due_at must be future, title.ar/en required

---

## Error Codes

| Code | HTTP | Message |
|------|------|---------|
| TASK_001 | 404 | Task not found |
| TASK_002 | 422 | Cannot start completed task |
| TASK_003 | 422 | Cannot complete not started task |
| TASK_004 | 403 | Not assigned to this task |

---

## Dependencies

**Requires:** Event, User, EventStaffingGroup

**Provides:** Task management, worker assignments

---

## Notifications & Events

### Events Emitted

| Event | When | Payload | Notification Recipient |
|-------|------|---------|------------------------|
| TaskAssigned | Task assigned to user | task_id, title, due_at, assigned_to, assigned_by | Assigned user |
| TaskStarted | Worker starts task | task_id, started_by, started_at | Task creator |
| TaskCompleted | Worker completes task | task_id, completed_by, completed_at | Task creator |
| TaskOverdue | Task passes due date | task_id, title, due_at, assigned_to | Assigned user, task creator |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class TaskAssigned
{
    public function __construct(
        public readonly TaskId $taskId,
        public readonly string $title,
        public readonly ?Carbon $dueAt,
        public readonly UserId $assignedTo,
        public readonly UserId $assignedBy,
        public readonly Carbon $occurredAt,
    ) {}
}

final class TaskStarted
{
    public function __construct(
        public readonly TaskId $taskId,
        public readonly UserId $startedBy,
        public readonly Carbon $startedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class TaskCompleted
{
    public function __construct(
        public readonly TaskId $taskId,
        public readonly UserId $completedBy,
        public readonly Carbon $completedAt,
        public readonly Carbon $occurredAt,
    ) {}
}

final class TaskOverdue
{
    public function __construct(
        public readonly TaskId $taskId,
        public readonly string $title,
        public readonly Carbon $dueAt,
        public readonly UserId $assignedTo,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

None.

---

## Next Steps

**After this module:** EventOperationalReport

**Commands:**
```bash
php artisan migrate:status | grep event_tasks
php artisan module:make EventOperationalReport
```

**Success:** Tasks assignable, status flow works, overdue auto-detection
