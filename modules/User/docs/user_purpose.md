# User Module Purpose

## Core Mission

The User module serves as the **identity and profile management backbone** of the Event Management System. It provides a unified repository for all human actors in the system — from individual workers to system controllers — and maintains their personal, contact, and financial information.

## What This Module Does

### 1. Identity Management

- **User Registration:** Creates new user accounts with phone as primary identifier
- **Account Activation:** System controllers can activate/deactivate accounts
- **Soft Deletion:** Remove access while preserving audit history
- **Phone Verification:** Validate phone numbers via OTP

### 2. Profile Management

- **Employee Profile:** Stores personal details (birth date, nationality, gender)
- **Medical Records:** Health conditions, allergies, medications
- **Physical Attributes:** Height (cm), weight (kg) for uniform/equipment sizing
- **Multi-language Names:** Arabic and English name fields

### 3. Emergency Contacts

- **Contact Phones:** Store emergency contact persons and their phone numbers
- **Relationship Tracking:** Father, Mother, Spouse, Sibling, Other
- **Multiple Contacts:** Users can have multiple emergency contacts

### 4. Financial Management

- **Bank Accounts:** Store IBAN, bank name, account holder name
- **Primary Account:** Mark one account as primary for payroll
- **Multiple Accounts:** Users can have multiple bank accounts (savings, salary, etc.)

### 5. Authentication Support

- **JWT Integration:** Users authenticate with phone + password
- **Password Hashing:** bcrypt with cost factor 12
- **Remember Token:** Laravel's built-in remember me functionality

## What This Module Does NOT Do

| Responsibility               | Handled By                |
| ---------------------------- | ------------------------- |
| Role assignment              | Role module               |
| Permission checking          | IAM module                |
| User login/logout            | IAM module (JWT)          |
| Password reset               | IAM module                |
| Event participation tracking | EventParticipation module |
| Contract management          | EventContract module      |
| Attendance tracking          | EventAttendance module    |
| Task assignments             | EventTask module          |

## Business Value

### For System Controllers

- Complete visibility of all users in the system
- Ability to activate/deactivate accounts
- Search and filter users by phone, email, national ID
- Audit user creation and modification history

### For General/Operations Managers

- View user profiles for reporting
- Access bank details for payroll processing
- See emergency contacts for safety purposes

### For Individual Users (Workers)

- Manage own profile information
- Add/update emergency contacts
- Add/change bank account details
- Set primary bank account for salary

### For Integration with Other Modules

- **EventParticipation:** Links users to events they work in
- **EventContract:** Associates contracts with users
- **EventAttendance:** Tracks user check-in/out
- **ParticipationEvaluation:** Stores performance reviews
- **EventTask:** Assigns tasks to users
- **EventAnnouncement:** Tracks who sent/received announcements
- **FileAttachment:** Records who uploaded files

## Key Use Cases

### Use Case 1: Worker Registration

```

1. Worker provides: name (ar/en), phone, email (optional), password
2. System creates user with is_active = false
3. System assigns default INDIVIDUAL role (via Role module)
4. Worker receives SMS with verification code
5. Worker verifies phone → phone_verified_at set
6. System controller activates account → is_active = true
7. Worker can now log in

```

### Use Case 2: Profile Completion

```

1. Worker logs in
2. Adds employee profile: birth_date, nationality, gender
3. Adds medical record: allergies, conditions
4. Adds emergency contact: name, phone, relationship
5. Adds bank details: IBAN, bank name, account owner
6. System validates IBAN format and uniqueness

```

### Use Case 3: Bank Account Management

```

1. Worker adds multiple bank accounts
2. Marks one account as primary (is_primary = true)
3. System ensures only one primary account per user
4. Payroll module reads primary bank detail for salary
5. Worker can change primary account at any time

```

### Use Case 4: Account Deactivation

```

1. System controller identifies inactive/terminated worker
2. Triggers account deactivation
3. System sets is_active = false
4. Worker cannot log in
5. All historical data (participations, contracts) remains intact
6. System controller can reactivate if needed

```

## Success Metrics

- **Registration time:** < 30 seconds for worker
- **Profile completion rate:** Target 95% before event start
- **Bank account verification:** 100% of active workers have primary bank account
- **Phone verification rate:** 100% of active workers have verified phone

## Failure Scenarios Handled

| Scenario                     | Mitigation                                   |
| ---------------------------- | -------------------------------------------- |
| Duplicate phone registration | Unique constraint + friendly error message   |
| Duplicate email              | Unique constraint (nullable allows null)     |
| Invalid phone format         | Regex validation for E.164 format            |
| Missing Arabic name          | Validation rule: name.ar required            |
| Missing English name         | Validation rule: name.en required            |
| Self-deactivation attempt    | Business rule: cannot deactivate own account |
| Inactive user login          | Returns 403 with "Account is inactive"       |
| Soft-deleted user login      | Returns 404 (user not found)                 |

## Integration Points

### Inbound (This module provides to others)

```

UserRepositoryInterface → Other modules fetch users by ID, phone, email
UserPresenters → Other modules format user data for API responses
User Domain Events → Other modules listen to UserRegistered, UserActivated

```

### Outbound (This module requires from others)

```

None — User module has zero external dependencies

```

## Compliance & Regulations

| Requirement           | Implementation                                                          |
| --------------------- | ----------------------------------------------------------------------- |
| GDPR Right to Erasure | Soft delete preserves data; hard delete requires manual DB intervention |
| Data Minimization     | Only collects necessary fields (no excessive personal data)             |
| Secure Storage        | Passwords hashed; sensitive fields (IBAN) encrypted at rest             |
| Audit Trail           | created_at, updated_at, deleted_at timestamps                           |
| Language Rights       | All name fields support Arabic and English                              |

## Future Enhancements (Not in MVP)

- [ ] Two-factor authentication (2FA)
- [ ] Biometric login (fingerprint/face)
- [ ] Social login (Google, Apple, Twitter)
- [ ] Profile picture upload with cropping
- [ ] Document upload (ID copy, visa, passport)
- [ ] Emergency contact SMS notification
- [ ] Bank account verification via micro-deposits
- [ ] Bulk user import via CSV/Excel
- [ ] User self-service account deletion (with cooldown period)
- [ ] Login history and device tracking
