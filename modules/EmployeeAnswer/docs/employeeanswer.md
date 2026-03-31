# EmployeeAnswer Module

## Module Purpose

The EmployeeAnswer module stores individual answers for each question in a quiz attempt. Each answer links to an attempt (from EmployeeQuizAttempt), a question (from Question), and records the worker's selected option and whether it was correct. This module enables detailed quiz analytics, question difficulty tracking, and answer history review.

---

## Table Schema

### `employee_answers`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| attempt_id | uuid | FOREIGN KEY → employee_quiz_attempts.id, CASCADE DELETE | Parent attempt |
| question_id | uuid | FOREIGN KEY → questions.id, RESTRICT | Question being answered |
| selected_option | json | NULLABLE | For multiple_choice: selected option value/label; for true_false: true/false |
| is_correct | boolean | DEFAULT: false | Whether answer matches correct answer |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique Constraint:** `(attempt_id, question_id)` — one answer per question per attempt.

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_106700_create_employee_answers_table.php` | Wave 6 | #26 | employee_quiz_attempts, questions |

**Position:** Wave 6 — after employee_quiz_attempts, before event_attendance_records.

---

## Relations

### Foreign Keys
- `employee_answers.attempt_id` → `employee_quiz_attempts.id` (CASCADE DELETE)
- `employee_answers.question_id` → `questions.id` (RESTRICT)

### Eloquent Relationships
```php
// EmployeeAnswerModel
public function attempt(): BelongsTo  // EmployeeQuizAttempt
public function question(): BelongsTo  // Question
```

---

## Execution Order

**Build Sequence Position:** Wave 6, #26 — after attempts, before attendance.

```
Wave 6:
  #25: employee_quiz_attempts
  #26: employee_answers ← YOU ARE HERE
  #27: event_attendance_records
```

**Service Provider Registration:** After EmployeeQuizAttempt, before EventAttendance.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| EmployeeQuizAttempt | `attempts` table | attempt_id FK |
| Question | `questions` table, correct_answer | Validate answer, determine is_correct |

### What Answer Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| EmployeeQuizAttempt | Answers | Calculate score |
| Reporting | Answer data | Question difficulty analysis |

---

## Domain Entities

### Aggregate Root: `EmployeeAnswer` (child of Attempt)

**Identity:** AnswerId (UUID)

**Core Attributes:**
- **AttemptId:** Reference to parent attempt
- **QuestionId:** Reference to question
- **SelectedOption:** Mixed (string for multiple_choice, boolean for true_false)
- **IsCorrect:** Boolean — calculated by comparing selected_option to question's correct answer

**Business Rules:**
- One answer per (attempt, question)
- Cannot modify answer after attempt completed
- Selected_option format depends on question_type
- Is_correct calculated automatically on save

### Value Objects
- **AnswerId:** UUID wrapper
- **SelectedOptionValue:** String or boolean, validated against question options

### Repository Interface: `AnswerRepositoryInterface`
- `save(Answer $answer): void`
- `findById(AnswerId $id): ?Answer`
- `findByAttempt(AttemptId $attemptId): array`
- `findByQuestion(QuestionId $questionId): array` (for analytics)
- `deleteByAttempt(AttemptId $attemptId): void` (bulk delete)
- `countCorrectByAttempt(AttemptId $attemptId): int`

### Domain Events
- `AnswerRecorded` — When answer saved
- `AnswersBulkRecorded` — When all answers submitted (batch)

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `RecordAnswer` | attempt_id, question_id, selected_option | Saves answer, calculates is_correct, updates attempt score (if completed) |
| `BulkRecordAnswers` | attempt_id, answers array | Batch save all answers, then auto-submit attempt |
| `UpdateAnswer` | answer_id, selected_option | Updates (only if attempt not completed) |
| `DeleteAnswersByAttempt` | attempt_id | Removes all answers for attempt |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetAnswer` | answer_id | Single answer with question details |
| `ListAnswersByAttempt` | attempt_id | All answers for attempt with question text |
| `GetAnswerSummaryByQuestion` | question_id | Statistics (total answers, correct rate) |

---

## API Endpoints

Base path: `/api/v1/quiz-attempts/{attempt_id}/answers`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/` | BulkRecordAnswersAction | Required | Worker (self) |
| GET | `/` | ListAnswersAction | Required | Worker (self), project_manager |
| GET | `/{id}` | GetAnswerAction | Required | As above |
| PUT | `/{id}` | UpdateAnswerAction | Required | Worker (self, only if incomplete) |

### Request/Response Examples

**POST /quiz-attempts/{attempt_id}/answers** (Bulk submit)
Request:
```json
{
    "answers": [
        {"question_id": "q1", "selected_option": "Team management"},
        {"question_id": "q2", "selected_option": true},
        {"question_id": "q3", "selected_option": "Call supervisor"}
    ]
}
```
Response (200):
```json
{
    "attempt_id": "attempt_uuid",
    "total_questions": 10,
    "correct_answers": 8,
    "score": 80.0,
    "is_passed": true,
    "answers": [
        {
            "question_id": "q1",
            "selected_option": "Team management",
            "is_correct": true
        },
        {
            "question_id": "q2",
            "selected_option": true,
            "is_correct": true
        }
    ]
}
```

**GET /quiz-attempts/{attempt_id}/answers**
Response:
```json
{
    "attempt_id": "attempt_uuid",
    "answers": [
        {
            "id": "a1",
            "question": {
                "id": "q1",
                "text": {"ar": "ما هي صلاحيات المشرف؟", "en": "What are supervisor permissions?"},
                "type": "multiple_choice"
            },
            "selected_option": "Team management",
            "is_correct": true,
            "correct_option": "Team management"
        },
        {
            "id": "a2",
            "question": {
                "id": "q2",
                "text": {"ar": "المشرف يمكنه إنشاء باركود", "en": "Supervisor can generate barcode"},
                "type": "true_false"
            },
            "selected_option": true,
            "is_correct": true,
            "correct_option": true
        }
    ],
    "summary": {
        "correct_count": 8,
        "incorrect_count": 2,
        "score": 80.0
    }
}
```

---

## Presenters API Response Format

### AnswerPresenter (for worker review)
- id, question_id, selected_option, is_correct
- correct_option (only shown after attempt completed)
- question_text (embedded)

### AnswerSummaryPresenter (for admin analytics)
- question_id, question_text
- total_answers, correct_count, correct_rate (percentage)

---

## Seeder Data

### AnswerSeeder
Creates sample answers for existing attempts:

| Attempt | Question | Selected Option | Correct |
|---------|----------|-----------------|---------|
| Attempt 1 (passed) | Security Q1 | Team management | Yes |
| Attempt 1 (passed) | Security Q2 | True | Yes |
| Attempt 1 (passed) | Security Q3 | Call supervisor | Yes |
| Attempt 2 (failed) | Security Q1 | Hire managers | No |
| Attempt 2 (failed) | Security Q2 | False | No |

**Dependencies:** Requires attempts and questions.

**Run order:** After AttemptSeeder.

---

## Infrastructure Implementation

### Eloquent Model: AnswerModel
- Table: `employee_answers`
- Casts: `selected_option` → array, `is_correct` → boolean
- Relationships: `attempt()`, `question()`

### EloquentAnswerRepository
Implements AnswerRepositoryInterface.

**Key methods:**
- `save()` → creates/updates answer, auto-calculates is_correct
- `findByAttempt()` → eager loads question
- `countCorrectByAttempt()` → for score calculation
- `deleteByAttempt()` → cascade delete

### Reflector: AnswerReflector
Converts between AnswerModel and Answer domain entity:
- Model → Domain: reconstructs with QuestionId, SelectedOption value object
- Domain → Model: maps attributes

### Answer Validation Service
- Validates selected_option against question.options
- For multiple_choice: must match one option value
- For true_false: must be boolean
- Calculates is_correct by comparing to correct_answer

---

## Service Provider Registration

**Class:** `Modules\EmployeeAnswer\Infrastructure\Providers\EmployeeAnswerServiceProvider`

**Register method:** Binds AnswerRepositoryInterface to EloquentAnswerRepository

**Boot method:** Loads migrations, loads routes

**Position:** After EmployeeQuizAttempt, before EventAttendance.

---

## Testing Strategy

### Unit Tests
- Answer validation against question type
- is_correct calculation (multiple_choice)
- is_correct calculation (true_false)
- Cannot answer same question twice per attempt

### Feature Tests
- Bulk submit answers → 200, score calculated
- Submit with invalid option → 422
- Update answer before completion → 200
- Update answer after completion → 422
- Retrieve answers for attempt → 200, correct_option shown only after completion

### Integration Tests
- Answer + Attempt: answers affect attempt score
- Answer + Question: uses question.correct_answer for validation
- Bulk submit auto-completes attempt

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Record/update answers | Worker (own attempt only) |
| View answers (own) | Worker (self) |
| View answers (admin) | project_manager, general_manager |

### Validation Rules

**RecordAnswer/BulkRecordAnswers:**
- `attempt_id`: must belong to authenticated user
- `question_id`: must belong to quiz of this attempt
- `selected_option`: 
  - For multiple_choice: must be one of question.options values
  - For true_false: must be boolean
- Attempt must not be completed
- Attempt must not be expired

**Business Rules:**
- Cannot answer same question twice per attempt (unique constraint)
- All questions must be answered before auto-submit
- Answers locked after attempt completion

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| AnswerRecorded | After single answer | answer_id, attempt_id, question_id | None (batch preferred) |
| AnswersBulkRecorded | After bulk submit | attempt_id, answer_count, correct_count | Auto-submit attempt, trigger score calculation |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| ANS_001 | 404 | Answer not found | Invalid ID |
| ANS_002 | 422 | Invalid selected option | Option not in question.options |
| ANS_003 | 409 | Already answered this question | Duplicate (attempt_id, question_id) |
| ANS_004 | 422 | Cannot modify completed attempt | Update after completion |
| ANS_005 | 422 | Missing answers for some questions | Bulk submit with missing questions |
| ANS_006 | 422 | Question does not belong to this quiz | question_id mismatch |

---

## Performance Considerations

- **Indexes:** `(attempt_id, question_id)` unique, `question_id`, `is_correct`
- **Bulk operations:** Use `insert()` for batch answer saving (reduces queries)
- **Score calculation:** Use SQL `SUM(is_correct)` for fast scoring
- **Caching:** Answer statistics cached per question for 1 hour

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| EmployeeQuizAttempt | `attempts` table, attempt status | attempt_id FK, validate not completed |
| Question | `questions` table, correct_answer | Validate selected_option, calculate is_correct |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| EmployeeQuizAttempt | Answers data | Calculate attempt score |
| Reporting | Answer analytics | Question difficulty, pass rate by question |

---

## Next Steps After Building EmployeeAnswer Module

### Pre-Flight Checklist
- [ ] employee_answers table migrated
- [ ] Unique constraint (attempt_id, question_id) working
- [ ] Answer validation per question type works
- [ ] is_correct auto-calculated correctly
- [ ] Bulk submit calculates score correctly
- [ ] Cannot answer after attempt completed

### Immediate Next Module: ParticipationEvaluation

**Why ParticipationEvaluation next?**
- Evaluations are core to event closure gate
- Uses participation_id from EventParticipation
- Independent from quiz flow

### Build Order
```
EmployeeAnswer → ParticipationEvaluation → ParticipationViolation → EventTask
```

### Commands to Run
```bash
php artisan migrate:status | grep employee_answers
php artisan module:make ParticipationEvaluation
```

### Success Criteria
- [ ] Answers stored per question
- [ ] Score calculated correctly
- [ ] Bulk submit workflow works
- [ ] Cannot cheat (answers locked after completion)
- [ ] Analytics available per question

---

**EmployeeAnswer Module Specification Complete.**
