# EmployeeQuizAttempt Module

## Module Purpose

The EmployeeQuizAttempt module tracks worker attempts at quizzes. Each attempt records which worker took which quiz, when they started and completed, their score, and whether they passed. Linked to a specific event participation (since quizzes are taken as part of contract acceptance). Attempts are limited by quiz.max_attempts. This module works with EmployeeAnswer to store individual question responses.

---

## Table Schema

### `employee_quiz_attempts`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| user_id | uuid | FOREIGN KEY → users.id, RESTRICT | Worker taking quiz |
| quiz_id | uuid | FOREIGN KEY → quizzes.id, RESTRICT | Quiz being taken |
| event_participation_id | uuid | FOREIGN KEY → event_participations.id, CASCADE DELETE | Participation context |
| score | decimal(5,2) | NULLABLE | Percentage score (0-100) |
| is_passed | boolean | DEFAULT: false | Whether passed (score >= passing_score) |
| started_at | timestamp | NOT NULL | When attempt started |
| completed_at | timestamp | NULLABLE | When attempt submitted |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Validation Rules:**
- `score` between 0 and 100
- `completed_at` must be after `started_at`
- Maximum attempts per (user, quiz) = quiz.max_attempts

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_106600_create_employee_quiz_attempts_table.php` | Wave 6 | #25 | users, quizzes, event_participations |

**Position:** Wave 6 — after contract_acceptance_steps, before employee_answers.

---

## Relations

### Foreign Keys
- `employee_quiz_attempts.user_id` → `users.id` (RESTRICT)
- `employee_quiz_attempts.quiz_id` → `quizzes.id` (RESTRICT)
- `employee_quiz_attempts.event_participation_id` → `event_participations.id` (CASCADE DELETE)

### Tables That Reference Attempts
| Table | Foreign Key | Module |
|-------|-------------|--------|
| employee_answers | attempt_id | EmployeeAnswer |

### Eloquent Relationships
```php
// EmployeeQuizAttemptModel
public function user(): BelongsTo
public function quiz(): BelongsTo
public function participation(): BelongsTo
public function answers(): HasMany
```

---

## Execution Order

**Build Sequence Position:** Wave 6, #25 — after contracts, before answers.

```
Wave 6:
  #24: contract_acceptance_steps
  #25: employee_quiz_attempts ← YOU ARE HERE
  #26: employee_answers
```

**Service Provider Registration:** After ContractAcceptanceStep, before EmployeeAnswer.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| User | `users` table | user_id FK |
| Quiz | `quizzes` table, max_attempts, passing_score | Validate attempts, calculate pass |
| EventParticipation | `event_participations` table | participation context |

### What Attempt Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| EmployeeAnswer | attempt_id | Answers belong to attempt |
| EventContract | pass_quiz step | Verify quiz passed |
| Reporting | attempt data | Pass rates, completion metrics |

---

## Domain Entities

### Aggregate Root: `EmployeeQuizAttempt`

**Identity:** AttemptId (UUID)

**Core Attributes:**
- **UserId:** Reference to worker
- **QuizId:** Reference to quiz
- **ParticipationId:** Reference to event participation
- **Score:** Decimal (0-100) — null until completed
- **IsPassed:** Boolean — calculated automatically when score >= quiz.passing_score
- **StartedAt:** Timestamp
- **CompletedAt:** Timestamp (null until submitted)

**Business Rules:**
- Maximum attempts per (user, quiz) = quiz.max_attempts
- Cannot start attempt if max attempts reached
- Cannot submit without answering all questions
- Score calculated from correct answers / total questions
- Once submitted (completed), cannot be modified
- Incomplete attempts can be resumed (same started_at)

### Value Objects
- **AttemptId:** UUID wrapper
- **AttemptStatus:** IN_PROGRESS, COMPLETED, EXPIRED (if timer exceeded)

### Repository Interface: `AttemptRepositoryInterface`
- `save(Attempt $attempt): void`
- `findById(AttemptId $id): ?Attempt`
- `findByUserAndQuiz(UserId $userId, QuizId $quizId): array` (all attempts)
- `findActiveByUserAndQuiz(UserId $userId, QuizId $quizId): ?Attempt` (incomplete)
- `countAttemptsByUserAndQuiz(UserId $userId, QuizId $quizId): int`
- `findByParticipation(ParticipationId $participationId): array`

### Domain Events
- `AttemptStarted` — When worker begins quiz
- `AttemptCompleted` — When quiz submitted
- `AttemptPassed` — When score >= passing_score
- `AttemptFailed` — When score < passing_score
- `MaxAttemptsReached` — When last attempt fails

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `StartAttempt` | user_id, quiz_id, participation_id | Creates attempt with started_at = now, validates max attempts not reached |
| `SubmitAttempt` | attempt_id, answers (array of question_id + selected_option) | Calculates score, sets completed_at, determines is_passed |
| `AutoFailExpiredAttempts` | (scheduled) | Fails attempts exceeding timer_minutes |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetAttempt` | attempt_id | Full attempt with answers |
| `GetBestAttemptByQuiz` | user_id, quiz_id | Highest scoring attempt |
| `GetAttemptStatus` | attempt_id | In progress, completed, or expired |

---

## API Endpoints

Base path: `/api/v1/quizzes`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/{quiz_id}/attempts/start` | StartAttemptAction | Required | Worker (self) |
| POST | `/{quiz_id}/attempts/{attempt_id}/submit` | SubmitAttemptAction | Required | Worker (self) |
| GET | `/attempts/{attempt_id}` | GetAttemptAction | Required | Worker (self), project_manager |
| GET | `/my-attempts` | MyAttemptsAction | Required | Worker (self) |
| GET | `/quizzes/{quiz_id}/attempts/stats` | QuizAttemptStatsAction | Required | project_manager |

### Request/Response Examples

**POST /quizzes/{quiz_id}/attempts/start**
Request:
```json
{
    "participation_id": "participation_uuid"
}
```
Response (200):
```json
{
    "id": "attempt_uuid",
    "quiz_id": "quiz_uuid",
    "started_at": "2026-06-01T10:00:00Z",
    "time_limit_minutes": 30,
    "expires_at": "2026-06-01T10:30:00Z",
    "questions": [
        {
            "id": "q1",
            "text": {"ar": "ما هي صلاحيات المشرف؟", "en": "What are supervisor permissions?"},
            "type": "multiple_choice",
            "options": ["Team management", "Hire managers", "Sign contracts"]
        }
    ]
}
```

**POST /quizzes/{quiz_id}/attempts/{attempt_id}/submit**
Request:
```json
{
    "answers": [
        {"question_id": "q1", "selected_option": "Team management"},
        {"question_id": "q2", "selected_option": "True"}
    ]
}
```
Response (200):
```json
{
    "id": "attempt_uuid",
    "score": 85.0,
    "is_passed": true,
    "passed_at": "2026-06-01T10:25:00Z",
    "correct_answers": 8,
    "total_questions": 10,
    "message": "Congratulations! You passed the quiz."
}
```

**GET /my-attempts**
Response:
```json
{
    "data": [
        {
            "id": "a1",
            "quiz": {"id": "q1", "title": {"ar": "اختبار الأمن", "en": "Security Test"}},
            "score": 85.0,
            "is_passed": true,
            "completed_at": "2026-06-01T10:25:00Z"
        },
        {
            "id": "a2",
            "quiz": {"id": "q1", "title": {"ar": "اختبار الأمن", "en": "Security Test"}},
            "score": 65.0,
            "is_passed": false,
            "completed_at": "2026-05-30T14:00:00Z"
        }
    ],
    "meta": {"total_attempts": 2, "remaining_attempts": 1}
}
```

---

## Presenters API Response Format

### AttemptPresenter
- id, quiz_id, score, is_passed
- started_at, completed_at
- time_spent_seconds (if completed)
- Embedded user summary (id, name)
- Embedded participation summary (event name)

### AttemptStatusPresenter (for in-progress)
- id, started_at, expires_at
- time_remaining_seconds
- questions array (without correct answers)

---

## Seeder Data

### AttemptSeeder
Creates sample attempts:

| Worker | Quiz | Score | Passed | Status |
|--------|------|-------|--------|--------|
| Employee 1 | Security Test | 85 | Yes | Completed |
| Employee 2 | Security Test | 65 | No | Completed |
| Employee 2 | Security Test | 90 | Yes | Completed |
| Employee 3 | Security Test | null | No | In progress |

**Dependencies:** Requires users, quizzes, participations.

**Run order:** After QuizSeeder, ParticipationSeeder.

---

## Infrastructure Implementation

### Eloquent Model: AttemptModel
- Table: `employee_quiz_attempts`
- Casts: `score` → decimal, `is_passed` → boolean, `started_at` → datetime, `completed_at` → datetime
- Relationships: `user()`, `quiz()`, `participation()`, `answers()`

### EloquentAttemptRepository
Implements AttemptRepositoryInterface.

**Key methods:**
- `save()` → creates/updates attempt
- `countAttemptsByUserAndQuiz()` → for max attempts validation
- `findActiveByUserAndQuiz()` → returns incomplete attempt (if exists)
- `findByParticipation()` → all attempts for contract acceptance flow

### Reflector: AttemptReflector
Converts between AttemptModel and Attempt domain entity.

---

## Service Provider Registration

**Class:** `Modules\EmployeeQuizAttempt\Infrastructure\Providers\EmployeeQuizAttemptServiceProvider`

**Register method:** Binds AttemptRepositoryInterface to EloquentAttemptRepository

**Boot method:** Loads migrations, loads routes, schedules `auto-fail-expired` command every minute

**Position:** After ContractAcceptanceStep, before EmployeeAnswer.

---

## Testing Strategy

### Unit Tests
- Max attempts validation (cannot start beyond limit)
- Score calculation (correct/total * 100)
- Pass determination (score >= passing_score)
- Cannot submit without all questions answered

### Feature Tests
- Start attempt → 200, questions returned
- Submit with all answers → 200, score calculated
- Submit with missing answers → 422
- Exceed max attempts → 422
- Submit after timer expires → 422 (expired)
- Non-owner cannot submit → 403

### Integration Tests
- Attempt + Quiz: uses quiz.max_attempts, passing_score
- Attempt + Answer: answers stored correctly
- Attempt + Contract: pass_quiz step completes when passed

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Start/submit attempt | Worker (self only) |
| View own attempts | Worker (self) |
| View any attempt | project_manager, general_manager |

### Validation Rules

**StartAttempt:**
- `user_id`: must match authenticated user
- `quiz_id`: exists, is_active = true
- `participation_id`: exists, belongs to user
- Max attempts not exceeded (count < quiz.max_attempts)

**SubmitAttempt:**
- Attempt must belong to user
- Attempt must not be completed
- Attempt must not be expired (if timer set)
- Answers count must equal quiz.question_count

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| AttemptStarted | After start | attempt_id, user_id, quiz_id | Track for analytics |
| AttemptCompleted | After submit | attempt_id, score, is_passed | If passed: trigger contract pass_quiz step |
| AttemptFailed | score < passing_score | attempt_id, score, remaining_attempts | Notify worker |
| MaxAttemptsReached | Last attempt fails | user_id, quiz_id | Notify admin |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| ATT_001 | 404 | Attempt not found | Invalid ID |
| ATT_002 | 422 | Max attempts reached | Count >= quiz.max_attempts |
| ATT_003 | 422 | Quiz not active | is_active = false |
| ATT_004 | 422 | Already completed | Resubmit attempt |
| ATT_005 | 422 | Attempt expired | Timer exceeded |
| ATT_006 | 422 | Missing answers | Answers count < question count |
| ATT_007 | 409 | No active attempt | Submit without start |

---

## Performance Considerations

- **Indexes:** `(user_id, quiz_id)`, `(quiz_id, is_passed)`, `participation_id`
- **Composite index:** `(user_id, quiz_id, completed_at)` for attempt history
- **Expiry job:** Runs every minute, auto-fails expired attempts
- **Caching:** Attempt count cached for 1 minute (cleared on start/submit)

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| User | `users` table | user_id FK |
| Quiz | `quizzes` table, max_attempts, passing_score | Validation and scoring |
| EventParticipation | `participations` table | participation context |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| EmployeeAnswer | attempt_id | Store individual answers |
| EventContract | is_passed | Complete pass_quiz step |

---

## Next Steps After Building EmployeeQuizAttempt Module

### Pre-Flight Checklist
- [ ] employee_quiz_attempts table migrated
- [ ] Max attempts validation working
- [ ] Score calculation correct
- [ ] Pass/fail determination correct
- [ ] Timer expiration handled
- [ ] Cannot submit without all answers

### Immediate Next Module: EmployeeAnswer

**Why EmployeeAnswer next?**
- Stores individual question responses
- References attempt_id from this module
- Completes the quiz-taking flow

### Build Order
```
EmployeeQuizAttempt → EmployeeAnswer → ParticipationEvaluation
```

### Commands to Run
```bash
php artisan migrate:status | grep employee_quiz_attempts
php artisan module:make EmployeeAnswer
```

### Success Criteria
- [ ] Workers can start quizzes
- [ ] Questions displayed without answers
- [ ] Answers submitted and scored correctly
- [ ] Passing quiz completes contract step
- [ ] Max attempts enforced
- [ ] Timer expiration enforced

---

**EmployeeQuizAttempt Module Specification Complete.**
