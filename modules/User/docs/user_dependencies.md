# User Module Dependencies

## Zero Inbound Dependencies

User module depends on **nothing**. It is the foundation.

| Dependency Type   | Status                        |
| ----------------- | ----------------------------- |
| Other modules     | None                          |
| External packages | None (uses only Laravel core) |
| Database          | MySQL/PostgreSQL (standard)   |
| Cache             | Redis/Memcached (optional)    |

---

## Outbound Dependencies (What Depends on User)

| Module                  | Depends On                      | Why                           |
| ----------------------- | ------------------------------- | ----------------------------- |
| Role                    | users table, UserRepository     | role_user pivot needs user_id |
| EventParticipation      | users table, UserId VO          | Links user to events          |
| EventContract           | users table (via participation) | Contract belongs to user      |
| EventAttendance         | users table (via participation) | Attendance tracks user        |
| EventTask               | users table (assigned_to)       | Tasks assigned to users       |
| EventAnnouncement       | users table (sender_id)         | Announcements sent by users   |
| FileAttachment          | users table (uploaded_by)       | Track who uploaded files      |
| DigitalSignature        | users table (user_id)           | Signatures belong to users    |
| EmployeeQuizAttempt     | users table (user_id)           | Quiz attempts by users        |
| EventExpense            | users table (submitted_by)      | Expense claims by users       |
| ParticipationEvaluation | users table (via participation) | Evaluations of users          |
| ParticipationViolation  | users table (via participation) | Violations by users           |
| EventAssetCustody       | users table (via participation) | Assets checked out to users   |
| EventOperationalReport  | users table (author_id)         | Reports authored by users     |
| EventRoleAssignment     | users table (user_id)           | Event-scoped roles            |
| EventRoleCapability     | users table (user_id)           | Fine-grained permissions      |

---

## Composer.json (User Module)

```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^12.0"
    },
    "autoload": {
        "psr-4": {
            "Modules\\User\\": "modules/User/"
        }
    }
}
```

No external packages required.

---

## Laravel Core Dependencies Used

| Facade/Contract                    | Purpose              |
| ---------------------------------- | -------------------- |
| Illuminate\Database\Eloquent\Model | Base model class     |
| Illuminate\Foundation\Auth\User    | Authenticatable base |
| Illuminate\Support\Facades\Hash    | Password hashing     |
| Illuminate\Support\Facades\DB      | Transactions         |
| Illuminate\Support\Facades\Cache   | Caching              |
| Illuminate\Support\Facades\Event   | Event dispatch       |

---

## Installation Order

```bash
# Step 1: User module first
composer require modules/user

# Step 2: Any other module (depends on User)
composer require modules/role
composer require modules/event-participation
# ... rest
```

---

## Breaking Changes Warning

Changes to User module **WILL break** all dependent modules if:

- UserRepositoryInterface method signature changes
- User domain entity public methods change
- Database column types change
- Foreign key names change

**Always maintain backward compatibility.**

---

## Dependency Graph

```
        ┌─────────────────┐
        │   User Module   │ (No dependencies)
        └────────┬────────┘
                 │
      ┌──────────┼──────────┐
      │          │          │
      ▼          ▼          ▼
   Role      Event       Contract
   Module    Module      Module
      │          │          │
      └──────────┼──────────┘
                 │
                 ▼
         All other modules
```
