# Question Module

## Module Purpose

The Question module manages individual questions within quizzes. Each question belongs to one quiz. Question types: multiple_choice (single answer from options) or true_false. Questions have multilingual text, configurable options (for multiple choice), and sort order. This module feeds into EmployeeAnswer when workers take quizzes.

---

## Table Schema

### `questions`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | uuid | PRIMARY KEY | Auto-generated UUID |
| quiz_id | uuid | FOREIGN KEY → quizzes.id, CASCADE DELETE | Parent quiz |
| question_text | json | NOT NULL | Question in Arabic/English |
| question_type | string | NOT NULL | multiple_choice, true_false |
| options | json | NULLABLE | For multiple_choice: array of options with labels |
| sort_order | integer | DEFAULT: 0 | Display order within quiz |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Validation Rules:**
- `question_type` in: multiple_choice, true_false
- `options` required if type = multiple_choice (minimum 2 options)
- `options` must include correct_answer indicator

---

## Migration Details

| Migration File | Wave | Order | Dependencies |
|----------------|------|-------|--------------|
| `2026_03_25_104100_create_questions_table.php` | Wave 4 | #19 | quizzes |

**Position:** Wave 4 — after quizzes, before event_position_applications.

---

## Relations

### Foreign Keys
- `questions.quiz_id` → `quizzes.id` (CASCADE DELETE)

### Tables That Reference Questions
| Table | Foreign Key | Module |
|-------|-------------|--------|
| employee_answers | question_id | EmployeeAnswer |

### Eloquent Relationships
```php
// QuestionModel
public function quiz(): BelongsTo
public function answers(): HasMany  // EmployeeAnswer
```

---

## Execution Order

**Build Sequence Position:** Wave 4, #19 — after quizzes, before applications.

```
Wave 3:
  #15: quizzes

Wave 4:
  #19: questions ← YOU ARE HERE
  #20: event_position_applications
```

**Service Provider Registration:** After Quiz, before EventPositionApplication.

---

## What's Needed From Others

### Required Modules

| Module | What | Why |
|--------|------|-----|
| Quiz | `quizzes` table | quiz_id foreign key |

### What Question Module Provides to Others

| Recipient | What | Purpose |
|-----------|------|---------|
| EmployeeAnswer | question_id | Record worker's answer |
| Quiz | question_count | Auto-calculate count |

---

## Domain Entities

### Aggregate Root: `Question` (child of Quiz)

**Identity:** QuestionId (UUID)

**Core Attributes:**
- **QuizId:** Reference to parent quiz
- **QuestionText:** TranslatableText (Arabic/English)
- **QuestionType:** Enum (MULTIPLE_CHOICE, TRUE_FALSE)
- **Options:** Array of Option objects (for multiple_choice)
- **SortOrder:** Integer

**Business Rules:**
- Multiple choice requires at least 2 options
- Each option must have a label and correct flag
- True/false has no options (hardcoded True/False)
- Sort order determines display sequence
- Cannot delete question if answers exist

### Value Objects
- **QuestionId:** UUID wrapper
- **QuestionTypeEnum:** MULTIPLE_CHOICE, TRUE_FALSE
- **Option:** Contains label (TranslatableText), is_correct (boolean), value (string)

### Repository Interface: `QuestionRepositoryInterface`
- `save(Question $question): void`
- `findById(QuestionId $id): ?Question`
- `findByQuiz(QuizId $quizId): array` (ordered by sort_order)
- `countByQuiz(QuizId $quizId): int`
- `delete(QuestionId $id): void`
- `hasAnswers(QuestionId $id): bool`

### Domain Events
- `QuestionCreated` — When question added to quiz
- `QuestionDeleted` — When question removed
- `QuizQuestionCountUpdated` — Triggers quiz.question_count recalculation

---

## CQRS Commands

### Commands (Write)
| Command | Input | Behavior |
|---------|-------|----------|
| `CreateQuestion` | quiz_id, question_text, question_type, options, sort_order | Adds question to quiz |
| `UpdateQuestion` | question_id, question_text, options, sort_order | Updates (fails if answers exist) |
| `DeleteQuestion` | question_id | Removes (fails if answers exist) |
| `ReorderQuestions` | quiz_id, question_id_order_array | Bulk update sort_order |

### Queries (Read)
| Query | Input | Output |
|-------|-------|--------|
| `GetQuestion` | question_id | Full question with options |
| `ListQuestionsByQuiz` | quiz_id, pagination | Paginated questions |
| `GetRandomQuestions` | quiz_id, count | Random subset for quiz retake |

---

## API Endpoints

Base path: `/api/v1/quizzes/{quiz_id}/questions`

| Method | URI | Action | Auth | Roles Allowed |
|--------|-----|--------|------|---------------|
| POST | `/` | CreateQuestionAction | Required | project_manager |
| GET | `/` | ListQuestionsAction | Required | project_manager, worker (taking quiz) |
| GET | `/{id}` | GetQuestionAction | Required | As above |
| PUT | `/{id}` | UpdateQuestionAction | Required | project_manager |
| DELETE | `/{id}` | DeleteQuestionAction | Required | project_manager |
| POST | `/reorder` | ReorderQuestionsAction | Required | project_manager |

### Request/Response Examples

**POST /quizzes/{quiz_id}/questions**
Request (multiple_choice):
```json
{
    "question_text": {"ar": "ما هي صلاحيات المشرف؟", "en": "What are supervisor permissions?"},
    "question_type": "multiple_choice",
    "options": [
        {"label": {"ar": "إدارة الفريق", "en": "Team management"}, "is_correct": true},
        {"label": {"ar": "تعيين المديرين", "en": "Hire managers"}, "is_correct": false},
        {"label": {"ar": "توقيع العقود", "en": "Sign contracts"}, "is_correct": false}
    ],
    "sort_order": 1
}
```
Request (true_false):
```json
{
    "question_text": {"ar": "المشرف يمكنه إنشاء باركود", "en": "Supervisor can generate barcode"},
    "question_type": "true_false",
    "sort_order": 2
}
```
Response (201):
```json
{
    "id": "question_uuid",
    "question_text": {"ar": "ما هي صلاحيات المشرف؟", "en": "What are supervisor permissions?"},
    "question_type": "multiple_choice",
    "options": [...],
    "sort_order": 1
}
```

**GET /quizzes/{quiz_id}/questions**
Response:
```json
{
    "data": [
        {
            "id": "q1",
            "question_text": {"ar": "ما هي صلاحيات المشرف؟", "en": "What are supervisor permissions?"},
            "question_type": "multiple_choice",
            "options": [
                {"label": {"ar": "إدارة الفريق", "en": "Team management"}, "is_correct": true},
                {"label": {"ar": "تعيين المديرين", "en": "Hire managers"}, "is_correct": false}
            ],
            "sort_order": 1
        },
        {
            "id": "q2",
            "question_text": {"ar": "المشرف يمكنه إنشاء باركود", "en": "Supervisor can generate barcode"},
            "question_type": "true_false",
            "options": null,
            "sort_order": 2
        }
    ],
    "meta": {"total": 5, "quiz": {"id": "quiz_id", "title": "Security Test"}}
}
```

---

## Presenters API Response Format

### QuestionPresenter
- id, question_text (ar/en), question_type
- options (array for multiple_choice, null for true_false)
- sort_order
- **Note:** `is_correct` flag is NOT exposed to workers (only for admin view)

### QuestionForWorkerPresenter (when taking quiz)
- id, question_text, question_type
- options (labels only, NO is_correct flag)
- sort_order

---

## Seeder Data

### QuestionSeeder
Creates sample questions for each quiz:

**Quiz: Security Test**
| Question (en) | Type | Correct Answer |
|---------------|------|----------------|
| What is the first step in emergency? | multiple_choice | Call supervisor |
| Supervisor can generate barcode? | true_false | True |
| Where to report violations? | multiple_choice | Through app |

**Quiz: Ticket Test**
| Question (en) | Type | Correct Answer |
|---------------|------|----------------|
| VIP ticket price? | multiple_choice | 500 SAR |
| Tickets refundable? | true_false | False |

**Dependencies:** Requires quizzes.

**Run order:** After QuizSeeder.

---

## Infrastructure Implementation

### Eloquent Model: QuestionModel
- Table: `questions`
- Casts: `question_text` → array, `options` → array, `sort_order` → integer
- Relationships: `quiz()`, `answers()`

### EloquentQuestionRepository
Implements QuestionRepositoryInterface.

**Key methods:**
- `save()` → creates/updates question
- `findByQuiz()` → ordered by sort_order
- `hasAnswers()` → checks answers.exists()
- `countByQuiz()` → returns integer for quiz.question_count update

### Reflector: QuestionReflector
Converts between QuestionModel and Question domain entity:
- Model → Domain: reconstructs with Option objects
- Domain → Model: serializes options JSON

---

## Service Provider Registration

**Class:** `Modules\Question\Infrastructure\Providers\QuestionServiceProvider`

**Register method:** Binds QuestionRepositoryInterface to EloquentQuestionRepository

**Boot method:** Loads migrations, loads routes

**Position:** After Quiz, before EventPositionApplication.

---

## Testing Strategy

### Unit Tests
- Multiple choice requires at least 2 options
- True/false has no options
- Sort order validation
- Cannot delete question with answers

### Feature Tests
- Create multiple choice question → 201
- Create true/false question → 201
- Create multiple choice with 1 option → 422
- Update question with answers → 409
- Delete question with answers → 409
- Reorder questions → sort_order updated

### Integration Tests
- Question + Quiz: question_count auto-updates
- Question + EmployeeAnswer: answers reference question

---

## Security and Validation Rules

### Authorization Rules

| Action | Required Role |
|--------|---------------|
| Create/update/delete question | project_manager (own event) |
| View questions (admin) | project_manager, area_manager |
| View questions (worker) | Worker taking quiz (questions only, no correct answers) |

### Validation Rules

**CreateQuestion:**
- `quiz_id`: required, exists:quizzes
- `question_text.ar`: required, string
- `question_text.en`: required, string
- `question_type`: required, in:multiple_choice,true_false
- `options`: required if type = multiple_choice, array, min:2
- `options.*.label.ar`: required, string
- `options.*.label.en`: required, string
- `options.*.is_correct`: required, boolean (exactly one true per question)
- `sort_order`: integer, min:0

**Business Rules:**
- Cannot delete question if employee_answers exist
- Correct answer flag not exposed to workers

---

## Events Emitted

| Event | When | Payload | Listeners |
|-------|------|---------|-----------|
| QuestionCreated | After save | question_id, quiz_id | Update quiz.question_count |
| QuestionDeleted | Before delete | question_id, quiz_id | Update quiz.question_count |
| QuestionsReordered | After reorder | quiz_id, order_map | None |

---

## Error Handling

| Code | HTTP | Message | When |
|------|------|---------|------|
| QST_001 | 404 | Question not found | Invalid ID |
| QST_002 | 422 | Multiple choice requires at least 2 options | Options count < 2 |
| QST_003 | 422 | Multiple choice requires exactly one correct answer | Multiple correct flags |
| QST_004 | 409 | Cannot modify question with existing answers | Answers exist |
| QST_005 | 422 | Invalid question type | Not multiple_choice or true_false |
| QST_006 | 422 | True/false cannot have options | Options provided for true_false |

---

## Performance Considerations

- **Indexes:** `quiz_id`, `sort_order`, `(quiz_id, sort_order)`
- **Question count:** Quiz.question_count auto-updated via event listener
- **Caching:** Questions cached per quiz for 10 minutes (cleared on question change)
- **Random selection:** Use `inRandomOrder()` for quiz retakes

---

## Dependencies

### Required From Other Modules

| Module | What | Why |
|--------|------|-----|
| Quiz | `quizzes` table | quiz_id FK |

### Provided To Other Modules

| Recipient | What | Purpose |
|-----------|------|---------|
| EmployeeAnswer | question_id | Record worker answers |
| Quiz | question_count | Display question count |

---

## Next Steps After Building Question Module

### Pre-Flight Checklist
- [ ] questions table migrated
- [ ] QuestionSeeder executed
- [ ] Multiple choice questions have 2+ options
- [ ] Exactly one correct answer per multiple choice question
- [ ] Can reorder questions
- [ ] Cannot delete question with answers

### Immediate Next Module: EmployeeQuizAttempt

**Why EmployeeQuizAttempt next?**
- Tracks worker quiz attempts
- Uses quiz_id from Quiz module
- Uses question_id from Question module via EmployeeAnswer

### Build Order
```
Quiz → Question → EmployeeQuizAttempt → EmployeeAnswer
```

### Commands to Run
```bash
php artisan migrate:status | grep questions
php artisan module:make EmployeeQuizAttempt
```

### Success Criteria
- [ ] Questions created under quizzes
- [ ] Multiple choice and true/false both work
- [ ] Sort order controls display sequence
- [ ] Question count auto-updates on quiz
- [ ] Workers see questions without correct answers

---

**Question Module Specification Complete.**
