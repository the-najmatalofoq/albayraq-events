# User Module Relations

## Overview

The User module sits at the center of the Event Management System. The `users` table is referenced by **25+ other tables** across the application. This document maps all relationships — both internal (within the module) and external (with other modules).

---

## Internal Relations (Within User Module)

### users → employee_profiles

| Attribute       | Value                                                     |
| --------------- | --------------------------------------------------------- |
| **Type**        | One-to-One                                                |
| **Foreign Key** | `employee_profiles.user_id` → `users.id`                  |
| **Delete Rule** | `CASCADE` — deleting user deletes profile                 |
| **Nullability** | Profile can be null (user may not have completed profile) |
| **Cardinality** | Exactly 0 or 1 profile per user                           |

### users → contact_phones

| Attribute       | Value                                                |
| --------------- | ---------------------------------------------------- |
| **Type**        | One-to-Many                                          |
| **Foreign Key** | `contact_phones.user_id` → `users.id`                |
| **Delete Rule** | `CASCADE` — deleting user deletes all contact phones |
| **Nullability** | User can have 0, 1, or many contact phones           |
| **Cardinality** | 0 to N contacts per user                             |

### users → bank_details

| Attribute       | Value                                              |
| --------------- | -------------------------------------------------- |
| **Type**        | One-to-Many                                        |
| **Foreign Key** | `bank_details.user_id` → `users.id`                |
| **Delete Rule** | `CASCADE` — deleting user deletes all bank details |
| **Nullability** | User can have 0, 1, or many bank accounts          |
| **Cardinality** | 0 to N bank accounts per user                      |

---

## External Relations (Other Modules → users)

### Module: Role

| Relation        | Description                                                                            |
| --------------- | -------------------------------------------------------------------------------------- |
| **Table**       | `role_user` (pivot)                                                                    |
| **Foreign Key** | `role_user.user_id` → `users.id`                                                       |
| **Delete Rule** | `CASCADE`                                                                              |
| **Purpose**     | Assigns global roles (system_controller, general_manager, operations_manager) to users |

### Module: EventRoleAssignment

| Relation        | Description                                                                                      |
| --------------- | ------------------------------------------------------------------------------------------------ |
| **Table**       | `event_role_assignments`                                                                         |
| **Foreign Key** | `event_role_assignments.user_id` → `users.id`                                                    |
| **Delete Rule** | `CASCADE`                                                                                        |
| **Purpose**     | Assigns event-scoped roles (project_manager, area_manager, etc.) to users within specific events |

### Module: EventParticipation

| Relation        | Description                                                                       |
| --------------- | --------------------------------------------------------------------------------- |
| **Table**       | `event_participations`                                                            |
| **Foreign Key** | `event_participations.user_id` → `users.id`                                       |
| **Delete Rule** | `RESTRICT`                                                                        |
| **Purpose**     | Links users to events they work in. Cannot delete user with active participations |

### Module: EventContract

| Relation        | Description                                           |
| --------------- | ----------------------------------------------------- |
| **Table**       | `event_contracts`                                     |
| **Foreign Key** | `event_contracts.user_id` (via event_participations)  |
| **Delete Rule** | `RESTRICT` (indirect)                                 |
| **Purpose**     | Contracts belong to users through their participation |

### Module: EventAttendance

| Relation        | Description                                                   |
| --------------- | ------------------------------------------------------------- |
| **Table**       | `event_attendance_records`                                    |
| **Foreign Key** | `event_attendance_records.user_id` (via event_participations) |
| **Delete Rule** | `RESTRICT` (indirect)                                         |
| **Purpose**     | Tracks check-in/out for users                                 |

### Module: ParticipationEvaluation

| Relation        | Description                                                    |
| --------------- | -------------------------------------------------------------- |
| **Table**       | `participation_evaluations`                                    |
| **Foreign Key** | `participation_evaluations.user_id` (via event_participations) |
| **Delete Rule** | `RESTRICT` (indirect)                                          |
| **Purpose**     | Stores performance scores for users                            |

### Module: ParticipationViolation

| Relation        | Description                                                   |
| --------------- | ------------------------------------------------------------- |
| **Table**       | `participation_violations`                                    |
| **Foreign Key** | `participation_violations.user_id` (via event_participations) |
| **Delete Rule** | `RESTRICT` (indirect)                                         |
| **Purpose**     | Records violations committed by users                         |

### Module: EventTask

| Relation        | Description                            |
| --------------- | -------------------------------------- |
| **Table**       | `event_tasks`                          |
| **Foreign Key** | `event_tasks.assigned_to` → `users.id` |
| **Delete Rule** | `RESTRICT`                             |
| **Purpose**     | Tasks assigned to users                |

### Module: EventAnnouncement

| Relation        | Description                                  |
| --------------- | -------------------------------------------- |
| **Table**       | `event_announcements`                        |
| **Foreign Key** | `event_announcements.sender_id` → `users.id` |
| **Delete Rule** | `RESTRICT`                                   |
| **Purpose**     | Tracks who sent announcements                |

### Module: FileAttachment

| Relation        | Description                                                      |
| --------------- | ---------------------------------------------------------------- |
| **Table**       | `attachments`                                                    |
| **Foreign Key** | `attachments.uploaded_by` → `users.id`                           |
| **Delete Rule** | `SET NULL`                                                       |
| **Purpose**     | Records who uploaded files (preserves file even if user deleted) |

### Module: DigitalSignature

| Relation        | Description                                 |
| --------------- | ------------------------------------------- |
| **Table**       | `digital_signatures`                        |
| **Foreign Key** | `digital_signatures.user_id` → `users.id`   |
| **Delete Rule** | `RESTRICT`                                  |
| **Purpose**     | Associates signatures with users who signed |

### Module: EmployeeQuizAttempt

| Relation        | Description                                   |
| --------------- | --------------------------------------------- |
| **Table**       | `employee_quiz_attempts`                      |
| **Foreign Key** | `employee_quiz_attempts.user_id` → `users.id` |
| **Delete Rule** | `RESTRICT`                                    |
| **Purpose**     | Tracks quiz attempts by users                 |

### Module: EventAssetCustody

| Relation        | Description                                                |
| --------------- | ---------------------------------------------------------- |
| **Table**       | `event_asset_custodies`                                    |
| **Foreign Key** | `event_asset_custodies.user_id` (via event_participations) |
| **Delete Rule** | `RESTRICT` (indirect)                                      |
| **Purpose**     | Tracks assets checked out to users                         |

### Module: EventExpense

| Relation        | Description                                |
| --------------- | ------------------------------------------ |
| **Table**       | `event_expenses`                           |
| **Foreign Key** | `event_expenses.submitted_by` → `users.id` |
| **Delete Rule** | `RESTRICT`                                 |
| **Purpose**     | Records who submitted expense claims       |

---

## Foreign Key Constraints Summary

| Constraint                                    | Type     | Behavior                                    |
| --------------------------------------------- | -------- | ------------------------------------------- |
| `employee_profiles.user_id` → `users.id`      | CASCADE  | Delete user → delete profile                |
| `contact_phones.user_id` → `users.id`         | CASCADE  | Delete user → delete contacts               |
| `bank_details.user_id` → `users.id`           | CASCADE  | Delete user → delete bank details           |
| `role_user.user_id` → `users.id`              | CASCADE  | Delete user → remove role assignments       |
| `event_role_assignments.user_id` → `users.id` | CASCADE  | Delete user → remove event role assignments |
| `event_participations.user_id` → `users.id`   | RESTRICT | Cannot delete user with participations      |
| `event_tasks.assigned_to` → `users.id`        | RESTRICT | Cannot delete user with assigned tasks      |
| `event_announcements.sender_id` → `users.id`  | RESTRICT | Cannot delete user who sent announcements   |
| `attachments.uploaded_by` → `users.id`        | SET NULL | Delete user → set uploaded_by to NULL       |
| `digital_signatures.user_id` → `users.id`     | RESTRICT | Cannot delete user with signatures          |
| `employee_quiz_attempts.user_id` → `users.id` | RESTRICT | Cannot delete user with quiz attempts       |
| `event_expenses.submitted_by` → `users.id`    | RESTRICT | Cannot delete user with expense submissions |

---

## Eloquent Relationships (For Reference)

### Inside UserModel.php

```php
// Internal relationships
public function employeeProfile(): HasOne
public function contactPhones(): HasMany
public function bankDetails(): HasMany

// External relationships (to other modules)
public function roles(): BelongsToMany  // Role module
public function eventRoleAssignments(): HasMany  // EventRoleAssignment module
public function eventParticipations(): HasMany  // EventParticipation module
public function assignedTasks(): HasMany  // EventTask module
public function sentAnnouncements(): HasMany  // EventAnnouncement module
public function uploadedFiles(): HasMany  // FileAttachment module
public function digitalSignatures(): HasMany  // DigitalSignature module
public function quizAttempts(): HasMany  // EmployeeQuizAttempt module
public function submittedExpenses(): HasMany  // EventExpense module
```

### Inside Other Modules' Models

```php
// RoleModel.php
public function users(): BelongsToMany

// EventParticipationModel.php
public function user(): BelongsTo

// EventTaskModel.php
public function assignee(): BelongsTo  // foreign: assigned_to

// AttachmentModel.php
public function uploader(): BelongsTo  // foreign: uploaded_by
```

---

## Loading Relationships Performance

### Eager Loading Examples

```php
// Load user with all internal relations
UserModel::with(['employeeProfile', 'contactPhones', 'bankDetails'])->find($id);

// Load user with external relations
UserModel::with(['roles', 'eventParticipations.event'])->find($id);

// Load users for an event with their participations
EventModel::with(['participations.user.employeeProfile'])->find($eventId);
```

### Lazy Loading Warning

```php
// Avoid N+1 queries
$users = UserModel::all();
foreach ($users as $user) {
    // This triggers a separate query for each user
    $profile = $user->employeeProfile;  // BAD for large datasets
}
```

---

## Cascade Behavior Deep Dive

### When a user is deleted (soft delete):

1. `users.deleted_at` is set to current timestamp
2. **No cascade happens** — soft delete does not trigger foreign key cascade
3. Related records remain but their `user_id` still points to soft-deleted user
4. Queries should filter `WHERE deleted_at IS NULL` to exclude soft-deleted users

### When a user is force-deleted (hard delete):

1. `users` record is permanently removed
2. `employee_profiles` records cascade delete
3. `contact_phones` records cascade delete
4. `bank_details` records cascade delete
5. `role_user` pivot records cascade delete
6. `event_role_assignments` records cascade delete
7. All other tables with `RESTRICT` will **block** the deletion if records exist

### Hard delete prevention:

```php
// This will throw an exception if user has participations, tasks, etc.
$user->forceDelete();  // Fails if RESTRICT constraints are violated
```

---

## Migration Order for Foreign Keys

Because of these relations, migrations must run in this order:

1. **Wave 1:** `users` table (no dependencies)
2. **Wave 2:** `employee_profiles`, `contact_phones`, `bank_details` (depend on users)
3. **Wave 3:** `event_role_assignments`, `role_user` (depend on users)
4. **Wave 5:** `event_participations` (depends on users)
5. **Wave 7:** `event_tasks`, `event_announcements` (depend on users)

---

## Diagram: User at the Center

```
                    ┌─────────────────┐
                    │   Role Module   │
                    │   role_user     │
                    └────────┬────────┘
                             │
                             │ user_id
                             ▼
┌─────────────────┐    ┌─────────────┐    ┌─────────────────┐
│  EventRole      │    │             │    │  Event          │
│  Assignment     │───▶│    users    │◀───│  Participation  │
│  Module         │    │             │    │  Module         │
└─────────────────┘    └──────┬──────┘    └─────────────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
    ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
    │ Employee    │  │ Contact     │  │ Bank        │
    │ Profile     │  │ Phones      │  │ Details     │
    └─────────────┘  └─────────────┘  └─────────────┘
           │                │                │
           └────────────────┼────────────────┘
                            │
              ┌─────────────┼─────────────┐
              │             │             │
              ▼             ▼             ▼
    ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
    │ Event       │  │ Digital     │  │ Employee    │
    │ Task        │  │ Signature   │  │ Quiz Attempt│
    └─────────────┘  └─────────────┘  └─────────────┘
```

---

## Constraint Naming Convention

When manually writing foreign keys, use this naming pattern:

```
fk_{table}_{column}_references_{foreign_table}_{foreign_column}
```

Examples:

- `fk_employee_profiles_user_id_references_users_id`
- `fk_contact_phones_user_id_references_users_id`
- `fk_bank_details_user_id_references_users_id`

Laravel's `foreignUuid()->constrained()` automatically generates names following this pattern.

---

## Useful SQL Queries

### Find all users with incomplete profiles

```sql
SELECT u.id, u.phone
FROM users u
LEFT JOIN employee_profiles ep ON u.id = ep.user_id
WHERE ep.id IS NULL AND u.deleted_at IS NULL;
```

### Find users with no bank details

```sql
SELECT u.id, u.phone
FROM users u
LEFT JOIN bank_details bd ON u.id = bd.user_id
WHERE bd.id IS NULL AND u.deleted_at IS NULL AND u.is_active = true;
```

### Count participations per user

```sql
SELECT u.id, u.phone, COUNT(ep.id) as participation_count
FROM users u
LEFT JOIN event_participations ep ON u.id = ep.user_id
WHERE u.deleted_at IS NULL
GROUP BY u.id;
```
