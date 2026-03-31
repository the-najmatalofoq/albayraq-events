```markdown
# Quiz Module

## Module Purpose

The Quiz module manages comprehension quizzes for workers. Quizzes are event-scoped and used primarily in the contract acceptance flow (pass_quiz step). Each quiz has a passing score, maximum attempt limit, optional timer, and a set of questions. Workers must pass the quiz before their contract can be accepted. Quiz results are stored in EmployeeQuizAttempt, with answers in EmployeeAnswer.

---

## Table Schema

### `quizzes`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| event_id | uuid | FOREIGN KEY → events.id, CASCADE DELETE | Parent event |
| title | json | NULLABLE | Quiz title in Arabic/English |
| passing_score | tinyint | NOT NULL | Minimum score to pass (0-100) |
| max_attempts | tinyint | NOT NULL | Maximum allowed attempts (1-10) |
| timer_minutes | smallint | NULLABLE | Time limit in minutes (null = no limit) |
| question_count | smallint | NOT NULL | Number of questions (auto-calculated) |
| is_active | boolean | DEFAULT: true | Whether quiz is available |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Validation Rules:**
- `passing_score` between 0 and 100
- `max_attempts` between 1 and 10
- `timer_minutes` between 1 and 180 (if provided)

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_103100_create_quizzes_table.php` | Wave 3 | #15 | events |

**Position:** Wave 3 — after wages, before questions.

---

## Relations

### Foreign Keys
- `quizzes.event_id` → `events.id` (CASCADE DELETE)

### Tables That Reference Quizzes
| Table | Foreign Key | Module |
|-------|-------------|--------|
| questions | quiz_id | Question |
| employee_quiz_attempts | quiz_id | EmployeeQuizAttempt |

### Eloquent Relationships
```php
// QuizModel
public function event(): BelongsTo
public function questions(): HasMany
public function attempts(): HasMany
```

---

## Execution Order

**Build Sequence Position:** Wave 3, #15 — after wages, before questions.

```
Wave 3:
  #14: wages
  #15: quizzes ← YOU ARE HERE
  #16: event_staffing_groups
```

**Service Provider Registration:** After EventStaffingPosition, before Question.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| Event | `events` table | event_id foreign key |

### What Quiz Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| Question | quiz_id | Questions belong to quiz |
| EmployeeQuizAttempt | quiz_id | Track worker attempts |
| EventContract | passing_score | pass_quiz acceptance step |

---

## Domain Entities

### Aggregate Root: `Quiz`

**Identity:** QuizId (UUID)

**Core Attributes:**
- **EventId:** Reference to event
- **Title:** TranslatableText (optional)
- **PassingScore:** Integer (0-100)
- **MaxAttempts:** Integer (1-10)
- **TimerMinutes:** Integer (optional, 1-180)
- **QuestionCount:** Integer (auto-calculated from questions)
- **IsActive:** Boolean

**Business Rules:**
- Quiz must have at least 1 question
- Cannot activate quiz without questions
- Question count auto-updated when questions added/removed
- Cannot modify quiz if attempts exist

### Value Objects
- **QuizId:** UUID wrapper
- **PassingScore:** Integer with range validation
- **MaxAttempts:** Integer with range validation

### Repository Interface: `QuizRepositoryInterface`
- `save(Quiz $quiz): void`
- `findById(QuizId $id): ?Quiz`
- `findByEvent(EventId $eventId): array`
- `findActiveByEvent(EventId $eventId): array`
- `delete(QuizId $id): void`
- `hasAttempts(QuizId $id): bool`

### Domain Events
- `QuizCreated` — When quiz created
- `QuizActivated` — When is_active becomes true
- `QuizDeactivated` — When is_active becomes false
- `QuestionCountUpdated` — When questions added/removed

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `CreateQuiz` | event_id, title, passing_score, max_attempts, timer_minutes | Creates quiz with is_active = false |
| `UpdateQuiz` | quiz_id, title, passing_score, max_attempts, timer_minutes | Updates (fails if attempts exist) |
| `ActivateQuiz` | quiz_id | Sets is_active = true (requires questions exist) |
| `DeactivateQuiz` | quiz_id | Sets is_active = false |
| `DeleteQuiz` | quiz_id | Soft delete (fails if attempts exist) |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetQuiz` | quiz_id | Full quiz with question count |
| `ListQuizzesByEvent` | event_id, include_inactive | Paginated quizzes |

---

## API Endpoints

Base path: `/api/v1/events/{event_id}/quizzes`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/` | CreateQuizAction | Required | project_manager, general_manager |
| GET | `/` | ListQuizzesAction | Required | project_manager, area_manager, general_manager |
| GET | `/{id}` | GetQuizAction | Required | As above |
| PUT | `/{id}` | UpdateQuizAction | Required | project_manager |
| POST | `/{id}/activate` | ActivateQuizAction | Required | project_manager |
| POST | `/{id}/deactivate` | DeactivateQuizAction | Required | project_manager |
| DELETE | `/{id}` | DeleteQuizAction | Required | project_manager, system_controller |

### Request/Response Examples

**POST /events/{event_id}/quizzes**
Request:
```json
{
    "title": {"ar": "اختبار الأمن", "en": "Security Test"},
    "passing_score": 70,
    "max_attempts": 3,
    "timer_minutes": 30
}
```
Response (201):
```json
{
    "id": "quiz_uuid",
    "title": {"ar": "اختبار الأمن", "en": "Security Test"},
    "passing_score": 70,
    "max_attempts": 3,
    "timer_minutes": 30,
    "question_count": 0,
    "is_active": false
}
```

**GET /events/{event_id}/quizzes/{id}**
Response:
```json
{
    "id": "quiz_uuid",
    "event_id": "event_uuid",
    "title": {"ar": "اختبار الأمن", "en": "Security Test"},
    "passing_score": 70,
    "max_attempts": 3,
    "timer_minutes": 30,
    "question_count": 10,
    "is_active": true,
    "attempts_count": 45,
    "pass_rate": 72.5,
    "created_at": "2026-03-31T10:00:00Z"
}
```

---

## Presenters API Response Format

### QuizPresenter
- id, title, passing_score, max_attempts, timer_minutes, question_count, is_active
- attempts_count (total attempts)
- pass_rate (percentage of attempts that passed)

### QuizSummaryPresenter (for list views)
- id, title, passing_score, question_count, is_active

---

## Seeder Data

### QuizSeeder
Creates sample quizzes:

| Event | Title (ar/en) | Passing Score | Max Attempts | Timer (min) |
|-------|---------------|---------------|--------------|-------------|
| Tech Conference | اختبار الأمن / Security Test | 70 | 3 | 30 |
| Tech Conference | اختبار التذاكر / Ticket Test | 75 | 2 | 20 |
| Marketing Expo | اختبار التنسيق / Coordination Test | 80 | 3 | 45 |

**Dependencies:** Requires events.

**Run order:** After EventSeeder, before QuestionSeeder.

---

## Infrastructure Implementation

### Eloquent Model: QuizModel
- Table: `quizzes`
- Casts: `title` → array, `passing_score` → integer, `max_attempts` → integer, `timer_minutes` → integer, `question_count` → integer, `is_active` → boolean
- Relationships: `event()`, `questions()`, `attempts()`

### EloquentQuizRepository
Implements QuizRepositoryInterface.

**Key methods:**
- `save()` → creates/updates quiz
- `findActiveByEvent()` → where is_active = true
- `hasAttempts()` → checks attempts count > 0

### Reflector: QuizReflector
Converts between QuizModel and Quiz domain entity.

---

## Service Provider Registration

**Class:** `Modules\Quiz\Infrastructure\Providers\QuizServiceProvider`

**Register method:** Binds QuizRepositoryInterface to EloquentQuizRepository

**Boot method:** Loads migrations, loads routes

**Position:** After EventStaffingPosition, before Question.

---

## Testing Strategy

### Unit Tests
- Quiz creation with valid/invalid passing score
- Cannot activate without questions
- Max attempts validation (1-10)
- Timer validation (1-180 minutes)

### Feature Tests
- Create quiz → 201
- Activate quiz without questions → 422
- Update quiz with attempts → 409
- Delete quiz with attempts → 409
- Non-project_manager cannot create → 403

### Integration Tests
- Quiz + Question: questions belong to quiz, count auto-updates
- Quiz + EmployeeQuizAttempt: attempts reference quiz

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Create/update quiz | project_manager (own event), general_manager |
| Activate/deactivate | project_manager |
| Delete quiz | project_manager, system_controller |
| View quizzes | project_manager, area_manager, general_manager |

### Validation Rules

**CreateQuiz:**
- `title.ar`: nullable, string
- `title.en`: nullable, string
- `passing_score`: required, integer, min:0, max:100
- `max_attempts`: required, integer, min:1, max:10
- `timer_minutes`: nullable, integer, min:1, max:180

**Business Rules:**
- Cannot activate quiz with 0 questions
- Cannot modify quiz if attempts exist

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| QuizCreated | After save | quiz_id, event_id | None |
| QuizActivated | is_active → true | quiz_id | Notify workers |
| QuizDeactivated | is_active → false | quiz_id | Prevent new attempts |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| QZ_001 | 404 | Quiz not found | Invalid ID |
| QZ_002 | 422 | Cannot activate quiz without questions | question_count = 0 |
| QZ_003 | 409 | Cannot modify quiz with existing attempts | attempts exist |
| QZ_004 | 422 | Invalid passing score | Not between 0-100 |
| QZ_005 | 422 | Invalid max attempts | Not between 1-10 |
| QZ_006 | 409 | Cannot delete quiz with attempts | attempts exist |

---

## Performance Considerations

- **Indexes:** `event_id`, `is_active`, `(event_id, is_active)`
- **Question count:** Auto-calculated via `withCount('questions')`
- **Caching:** Quiz data cached for 10 minutes (cleared on update)
- **Pass rate:** Calculated via attempts aggregation

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| Event | `events` table | event_id FK |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| Question | quiz_id | Questions belong to quiz |
| EmployeeQuizAttempt | quiz_id | Track attempts |
| EventContract | passing_score | pass_quiz validation |

---

## Next Steps After Building Quiz Module

### Pre-Flight Checklist
- [ ] quizzes table migrated
- [ ] QuizSeeder executed
- [ ] Cannot activate quiz without questions
- [ ] Update blocked if attempts exist

### Immediate Next Module: Question

**Why Question next?**
- Questions belong to quizzes
- Quiz needs questions to be usable
- EmployeeAnswer references questions

### Build Order
```
Quiz → Question → EmployeeQuizAttempt → EmployeeAnswer
```

### Commands to Run
```bash
php artisan migrate:status | grep quizzes
php artisan module:make Question
```

### Success Criteria
- [ ] Quizzes created under events
- [ ] Passing score enforced
- [ ] Timer works (if set)
- [ ] Attempt limits enforced
- [ ] Quiz activation requires questions

---

**Quiz Module Specification Complete.**
