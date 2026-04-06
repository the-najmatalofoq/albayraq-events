# User Module Domain Entities

## Aggregate Root: User

The **User** is the sole aggregate root in this module. It encapsulates all user identity, profile, contact, and financial information. All access to related entities (employee profile, contact phones, bank details) must go through the User aggregate.

### Identity

- **User ID:** UUID value object — immutable, generated on creation
- **Name:** Translatable text value object — supports Arabic and English
- **Phone:** String — primary login identifier, unique across system
- **Email:** Optional string — must be unique if provided
- **National ID:** Optional string — must be unique if provided

### Authentication

- **Password:** Hashed string — never exposed in API responses
- **Remember Token:** String — Laravel's remember me functionality

### Account Status

- **Is Active:** Boolean — determines if user can log in
- **Phone Verified At:** Timestamp — null until phone verification complete
- **Deleted At:** Timestamp — soft delete marker

### Relationships (Managed Within Aggregate)

- **Employee Profile:** Optional — one-to-one, deleted when user deleted
- **Contact Phones:** Collection — zero or more, deleted when user deleted
- **Bank Details:** Collection — zero or more, deleted when user deleted

### Business Rules Enforced

| Rule                                          | Enforcement Point                   |
| --------------------------------------------- | ----------------------------------- |
| Phone must be unique                          | Repository check before save        |
| Email must be unique if provided              | Repository check before save        |
| National ID must be unique if provided        | Repository check before save        |
| Cannot activate already active user           | Domain exception                    |
| Cannot deactivate already inactive user       | Domain exception                    |
| Cannot verify already verified phone          | Domain exception                    |
| Only one primary bank detail allowed          | Domain method enforces              |
| Cannot add duplicate contact phone            | Domain checks existing phones       |
| Cannot add duplicate bank account (same IBAN) | Domain checks existing bank details |

### State Transitions

```
Registration
     │
     ▼
┌─────────────────────────────────────────────────────────────┐
│                      INACTIVE                                │
│  • is_active = false                                         │
│  • email_verified_at = null                                  │
│  • Can receive role assignments                              │
│  • Cannot log in                                             │
└─────────────────────────────────────────────────────────────┘
     │                              │
     │ activate()                   │ verifyEmail()
     ▼                              ▼
┌─────────────────┐          ┌─────────────────────────────────┐
│     ACTIVE      │          │      INACTIVE (VERIFIED)         │
│  • is_active=true│          │  • is_active=false               │
│  • Can log in   │          │  • email_verified_at = timestamp  │
└─────────────────┘          │  • Still cannot log in            │
     ▲                        └─────────────────────────────────┘
     │                                              │
     │ deactivate()                                 │ activate()
     │                                              ▼
     │                        ┌─────────────────────────────────┐
     └────────────────────────│       ACTIVE (VERIFIED)         │
                              │  • is_active = true              │
                              │  • email_verified_at = timestamp │
                              │  • Full system access            │
                              └─────────────────────────────────┘
```

---

## Value Objects

### UserId

- Wraps a UUID string
- Validates UUID format on construction
- Provides toString() method for database storage
- Can be generated (UUID v4) or created from existing string
- Immutable — once created cannot be changed

### TranslatableText (from Shared module, reused here)

- Wraps JSON structure: `{"ar": "Arabic text", "en": "English text"}`
- Validates both keys exist on creation
- Provides getArabic(), getEnglish(), toArray() methods
- Used for: user name

### EmployeeProfile

- **Full Name:** Translatable text — complete name in Arabic/English
- **Birth Date:** Date — must be before today, after 1900
- **Nationality:** String — country code (SA, EG, US, etc.)
- **Gender:** Enum — male, female, other
- **Medical Record:** Text — free text for allergies, conditions, medications
- **Height:** Integer — centimeters, range 100-250
- **Weight:** Integer — kilograms, range 30-300
- All fields optional — profile can be completed progressively

### ContactPhone

- **Name:** String — full name of emergency contact
- **Phone:** String — E.164 format, validated
- **Relationship:** String — Father, Mother, Spouse, Sibling, Other
- No ID needed at creation — ID generated when added to aggregate

### BankDetail

- **Account Owner Name:** String — name exactly as on bank account
- **Bank Name:** String — financial institution name
- **IBAN:** String — validated against IBAN format (not actual account verification)
- **Is Primary:** Boolean — determines which account receives salary
- Business rule: Only one bank detail per user can have is_primary = true
- No ID needed at creation — ID generated when added to aggregate

---

## Enums

### GenderEnum

- **MALE** — male
- **FEMALE** — female
- **OTHER** — other (non-binary, prefer not to say, etc.)

### ContactRelationshipEnum

- **FATHER** — Father
- **MOTHER** — Mother
- **SPOUSE** — Spouse
- **SIBLING** — Sibling
- **OTHER** — Other relationship

---

## Domain Events

### UserRegistered

Emitted when a new user completes registration.

- Contains: user_id, phone, email (if provided), occurred_at
- Listeners: Send welcome SMS, create default role assignments

### UserActivated

Emitted when system controller activates a user account.

- Contains: user_id, activated_by (admin user id), occurred_at
- Listeners: Send activation notification, trigger onboarding workflow

### UserDeactivated

Emitted when system controller deactivates a user account.

- Contains: user_id, deactivated_by, occurred_at
- Listeners: Revoke active sessions, send deactivation notice

### PhoneVerified

Emitted when user successfully verifies phone via OTP.

- Contains: user_id, verified_at
- Listeners: Update user status, trigger post-verification actions

### ProfileUpdated

Emitted when user updates employee profile.

- Contains: user_id, changed_fields (array of field names), occurred_at
- Listeners: Update search indexes, trigger profile completion checks

### BankDetailAdded

Emitted when user adds new bank account.

- Contains: user_id, bank_detail_id, is_primary
- Listeners: Update payroll system, trigger bank verification

### BankDetailRemoved

Emitted when user removes a bank account.

- Contains: user_id, bank_detail_id, was_primary
- Listeners: Update payroll system, warn if no primary account remains

### ContactPhoneAdded

Emitted when user adds emergency contact.

- Contains: user_id, contact_phone_id, relationship
- Listeners: None currently (for future emergency alert system)

---

## Domain Exceptions

| Exception                      | When Thrown                                   |
| ------------------------------ | --------------------------------------------- |
| UserNotFoundException          | User ID not found in repository               |
| DuplicatePhoneException        | Attempt to register existing phone            |
| DuplicateEmailException        | Attempt to register existing email            |
| DuplicateNationalIdException   | Attempt to register existing national ID      |
| InactiveUserException          | Attempt to log in with is_active = false      |
| UserAlreadyActiveException     | Attempt to activate already active user       |
| UserAlreadyInactiveException   | Attempt to deactivate already inactive user   |
| PhoneAlreadyVerifiedException  | Attempt to verify already verified phone      |
| NoPrimaryBankDetailException   | Payroll attempted but no primary bank account |
| DuplicateContactPhoneException | Same phone number already exists for user     |
| DuplicateBankDetailException   | Same IBAN already exists for user             |
| InvalidPhoneFormatException    | Phone doesn't match E.164 format              |
| InvalidEmailFormatException    | Email doesn't match RFC 5322                  |
| InvalidIbanFormatException     | IBAN doesn't match format requirements        |
| SelfDeactivationException      | User attempts to deactivate own account       |

---

## Repository Interface

### UserRepositoryInterface

The contract for storing and retrieving User aggregates. Implementation is in Infrastructure layer.

**Methods:**

- **save(User $user): void** — Persists user and all related value objects (employee profile, contact phones, bank details)
- **findById(UserId $id): ?User** — Retrieves complete user aggregate with all relations
- **findByPhone(string $phone): ?User** — Finds user by unique phone number
- **findByEmail(string $email): ?User** — Finds user by unique email (returns null if email not set)
- **findByNationalId(string $nationalId): ?User** — Finds user by national ID (returns null if not set)
- **findAllActive(): array** — Returns all users with is_active = true and deleted_at = null
- **findAllWithPendingProfile(): array** — Returns users without completed employee profile
- **delete(UserId $id): void** — Soft deletes user (sets deleted_at timestamp)
- **forceDelete(UserId $id): void** — Permanently removes user and cascades to related data

**Transaction Requirements:**

- Save operation must be atomic — all or nothing
- If any related value object fails to persist, entire operation rolls back
- Repository must handle both create and update (upsert logic)

---

## Aggregate Invariants (Always True)

1. User ID never changes after creation
2. Phone number never changes (system constraint — user cannot change phone)
3. Email can be added or changed (if unique)
4. National ID never changes after set
5. Password always stored as hash, never plain text
6. Phone verification timestamp only set once
7. User cannot be reactivated after soft delete (must be restored first)
8. Soft-deleted users retain all related data but are excluded from queries
9. Only one bank detail can have is_primary = true at any time
10. Deleting a user cascade-deletes employee_profile, contact_phones, bank_details
11. Role assignments are NOT cascade-deleted (preserved for auditing)
12. Event participations block deletion (RESTRICT constraint in database)

---

## Validation Rules Summary

| Field                         | Rules                                            |
| ----------------------------- | ------------------------------------------------ |
| User.name.ar                  | Required, string, max 255                        |
| User.name.en                  | Required, string, max 255                        |
| User.phone                    | Required, unique, regex E.164                    |
| User.email                    | Nullable, unique, email format                   |
| User.national_id              | Nullable, unique, string                         |
| User.password                 | Required, min 8 chars, confirmed                 |
| EmployeeProfile.birth_date    | Nullable, date, before today, after 1900         |
| EmployeeProfile.gender        | Nullable, in: male,female,other                  |
| EmployeeProfile.height        | Nullable, integer, min 100, max 250              |
| EmployeeProfile.weight        | Nullable, integer, min 30, max 300               |
| ContactPhone.name             | Required, string, max 255                        |
| ContactPhone.phone            | Required, regex E.164                            |
| ContactPhone.relationship     | Nullable, in: Father,Mother,Spouse,Sibling,Other |
| BankDetail.account_owner_name | Required, string, max 255                        |
| BankDetail.bank_name          | Required, string, max 255                        |
| BankDetail.iban               | Required, regex IBAN format                      |
| BankDetail.is_primary         | Boolean                                          |
