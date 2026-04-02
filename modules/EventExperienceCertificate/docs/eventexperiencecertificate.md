# EventExperienceCertificate Module

## Module Purpose

Generates experience certificates for workers after event closure. Each certificate is linked to a participation and includes total hours worked and average evaluation score. Certificates are issued automatically when an event transitions to CLOSED status (Gate 3 passed). Each certificate has a unique verification code for authenticity verification.

---

## Table Schema

### `event_experience_certificates`

| Column                 | Type         | Constraints                                          |
| ---------------------- | ------------ | ---------------------------------------------------- |
| id                     | uuid         | PK                                                   |
| event_participation_id | uuid         | FK → event_participations.id, UNIQUE, CASCADE DELETE |
| total_hours            | decimal(8,2) | NOT NULL                                             |
| average_score          | decimal(3,1) | NOT NULL                                             |
| issued_at              | timestamp    | NOT NULL                                             |
| verification_code      | string       | UNIQUE, NOT NULL                                     |
| created_at, updated_at | timestamps   |                                                      |

**Unique Constraint:** `event_participation_id` — one certificate per participation.

---

## Migration Details

| File                                                               | Wave   | Order |
| ------------------------------------------------------------------ | ------ | ----- |
| `2026_03_25_109000_create_event_experience_certificates_table.php` | Wave 6 | #34   |

**Depends on:** event_participations

---

## Relations

- `event_experience_certificates.event_participation_id` → `event_participations.id` (CASCADE DELETE)

---

## Execution Order

**Wave 6, #34** — after event_participation_badges, before event_tasks

**Service Provider:** After EventParticipationBadge, before EventTask

---

## What's Needed From Others

| Module                  | What                                               |
| ----------------------- | -------------------------------------------------- |
| EventParticipation      | participations table, status, started_at, ended_at |
| EventAttendance         | attendance records (total hours)                   |
| ParticipationEvaluation | evaluation scores (average)                        |
| Event                   | event closure status (trigger)                     |

---

## Domain Entities

**Aggregate Root:** `ExperienceCertificate`

**Attributes:** CertificateId, ParticipationId, TotalHours (decimal), AverageScore (decimal), IssuedAt (Carbon), VerificationCode (string, unique)

**Rules:** Generated only once per participation; verification code is random 16-character alphanumeric; total hours = sum of attendance records check-in/out hours; average_score = average of all evaluations for participation

**Repository:** `CertificateRepositoryInterface`

- save(), findById(), findByParticipation(), findByVerificationCode(), findByEvent(), generateForParticipation(), generateForEvent()

**Events:** CertificateIssued

---

## CQRS Commands

| Command                      | Input                                              |
| ---------------------------- | -------------------------------------------------- |
| GenerateCertificate          | participation_id (auto-triggered on event closure) |
| GenerateAllEventCertificates | event_id (batch after closure)                     |
| RegenerateCertificate        | participation_id (admin)                           |

| Query                   | Output                          |
| ----------------------- | ------------------------------- |
| GetCertificate          | Full certificate                |
| GetCertificateByCode    | verification_code → certificate |
| ListCertificatesByEvent | Paginated certificates          |

---

## API Endpoints

Base: `/api/v1/certificates`

| Method | URI                               | Roles                                          |
| ------ | --------------------------------- | ---------------------------------------------- |
| GET    | `/my-certificates`                | Worker (self)                                  |
| GET    | `/{id}`                           | Worker (own), project_manager, general_manager |
| GET    | `/verify/{code}`                  | Public (no auth)                               |
| GET    | `/events/{event_id}/certificates` | project_manager, general_manager               |

### Request/Response Examples

**GET /certificates/verify/ABC123XYZ**
Response:

```json
{
    "valid": true,
    "certificate": {
        "verification_code": "ABC123XYZ",
        "issued_at": "2026-06-10T10:00:00Z",
        "recipient_name": { "ar": "أحمد محمد", "en": "Ahmed Mohamed" },
        "event_name": { "ar": "مؤتمر التقنية", "en": "Tech Conference" },
        "total_hours": 24.5,
        "average_score": 8.2
    }
}
```

**GET /certificates/my-certificates**
Response:

```json
{
    "data": [
        {
            "id": "cert_uuid",
            "event": {
                "name": { "ar": "مؤتمر التقنية", "en": "Tech Conference" }
            },
            "total_hours": 24.5,
            "average_score": 8.2,
            "issued_at": "2026-06-10T10:00:00Z",
            "verification_code": "ABC123XYZ"
        }
    ]
}
```

---

## Presenters

**CertificatePresenter:** id, verification_code, total_hours, average_score, issued_at, event_name, recipient_name

**CertificateVerifyPresenter:** valid (bool), certificate (if valid)

---

## Seeder Data

**CertificateSeeder:** Sample certificates for completed participations

**Depends on:** participations, attendance records, evaluations

---

## Infrastructure

**Model:** CertificateModel

- Casts: total_hours → decimal, average_score → decimal, issued_at → datetime

**Repository:** EloquentCertificateRepository

**Reflector:** CertificateReflector

**Generator Service:**

- Calculates total hours from attendance records
- Calculates average_score from evaluations
- Generates unique verification code (16 chars, alphanumeric)
- Dispatches CertificateIssued event

---

## Testing

**Unit:** Verification code uniqueness, total hours calculation, average score calculation

**Feature:** Generate certificate on event closure, verify by code, list by user

**Integration:** Certificate + Participation, Certificate + Attendance, Certificate + Evaluation

---

## Security

| Action                      | Role                             |
| --------------------------- | -------------------------------- |
| View own certificates       | Worker (self)                    |
| View all event certificates | project_manager, general_manager |
| Verify certificate          | Public                           |
| Regenerate certificate      | system_controller                |

**Validation:** Certificate only generated for COMPLETED participations

---

## Error Codes

| Code     | HTTP | Message                                              |
| -------- | ---- | ---------------------------------------------------- |
| CERT_001 | 404  | Certificate not found                                |
| CERT_002 | 422  | Certificate already exists for this participation    |
| CERT_003 | 422  | Cannot generate certificate for active participation |
| CERT_004 | 404  | Invalid verification code                            |

---

## Dependencies

**Requires:** EventParticipation, EventAttendance, ParticipationEvaluation, Event

**Provides:** Experience certificates, public verification

---

## Notifications & Events

### Events Emitted

| Event             | When                  | Payload                                                                  | Notification Recipient |
| ----------------- | --------------------- | ------------------------------------------------------------------------ | ---------------------- |
| CertificateIssued | Certificate generated | certificate_id, participation_id, user_id, event_name, verification_code | Worker                 |

### Domain Event Classes

Create in `Domain/Events/`:

```php
final class CertificateIssued
{
    public function __construct(
        public readonly CertificateId $certificateId,
        public readonly ParticipationId $participationId,
        public readonly UserId $userId,
        public readonly string $eventName,
        public readonly string $verificationCode,
        public readonly Carbon $issuedAt,
        public readonly Carbon $occurredAt,
    ) {}
}
```

### Events Listened

Listens to `EventClosed` from Event module to trigger certificate generation.

---

## Next Steps

**After this module:** EventTask

**Commands:**

```bash
php artisan migrate:status | grep event_experience_certificates
php artisan module:make EventTask
```

**Success:** Certificates auto-generated on event closure; verification codes unique; public verification works
