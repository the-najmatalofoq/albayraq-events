# User Module CQRS Commands

## Command Pattern Overview

The User module uses CQRS (Command Query Responsibility Segregation). Commands modify state. Queries read state. No command returns data (except success/failure).

## Commands (Write Operations)

### RegisterUser

- **Input:** name (ar/en), phone, email (optional), password
- **Output:** UserId
- **Behavior:** Creates user with is_active=false, phone_verified_at=null, assigns INDIVIDUAL role
- **Validates:** Unique phone, unique email, phone format E.164
- **Emits:** UserRegistered event

### ActivateUser

- **Input:** user_id, activated_by (admin user id)
- **Behavior:** Sets is_active=true
- **Validates:** User exists, user not already active
- **Emits:** UserActivated event
- **Authorization:** system_controller only

### DeactivateUser

- **Input:** user_id, deactivated_by
- **Behavior:** Sets is_active=false, revokes all active sessions
- **Validates:** User exists, user not already inactive, cannot deactivate self
- **Emits:** UserDeactivated event
- **Authorization:** system_controller only

### VerifyPhone

- **Input:** user_id, verification_code (6-digit OTP)
- **Behavior:** Sets phone_verified_at = now()
- **Validates:** User exists, code matches, phone not already verified
- **Emits:** PhoneVerified event

### UpdateUserProfile

- **Input:** user_id, any profile fields (full_name, birth_date, nationality, gender, medical_record, height, weight)
- **Behavior:** Updates only provided fields, preserves existing values
- **Validates:** Field-level validation (gender enum, height/weight ranges)
- **Emits:** ProfileUpdated event with changed_fields array

### AddContactPhone

- **Input:** user_id, name, phone, relationship (optional)
- **Behavior:** Adds emergency contact to user's contact_phones collection
- **Validates:** Phone not already in user's contacts
- **Emits:** ContactPhoneAdded event

### RemoveContactPhone

- **Input:** user_id, contact_phone_id
- **Behavior:** Removes contact from user's collection
- **Validates:** Contact belongs to user

### AddBankDetail

- **Input:** user_id, account_owner_name, bank_name, iban, is_primary (default false)
- **Behavior:** Adds bank account to user's bank_details collection
- **Validates:** IBAN format, IBAN not already exists for user
- **Business Rule:** If is_primary=true, demotes existing primary
- **Emits:** BankDetailAdded event

### SetPrimaryBankDetail

- **Input:** user_id, bank_detail_id
- **Behavior:** Sets specified account as primary, demotes current primary
- **Validates:** Bank detail belongs to user
- **Emits:** None (just state change)

### RemoveBankDetail

- **Input:** user_id, bank_detail_id
- **Behavior:** Removes bank account
- **Validates:** Bank detail belongs to user, cannot remove if it's the only account
- **Emits:** BankDetailRemoved event

## Queries (Read Operations)

### GetUser

- **Input:** user_id
- **Output:** User data (id, name, phone, email, is_active, profile, contact_phones, bank_details)
- **Authorization:** User can view own profile; admins can view any

### ListUsers

- **Input:** filters (is_active, role, event_id, search_phone, search_email), pagination (page, per_page)
- **Output:** Paginated list of users with summary data (no sensitive fields)
- **Authorization:** system_controller, general_manager, operations_manager only

### FindUserByPhone

- **Input:** phone
- **Output:** User data or null
- **Authorization:** Internal use (authentication system)

## Command Handlers

Each command has a corresponding handler:

- RegisterUserHandler → orchestrates User registration flow
- ActivateUserHandler → checks permissions, activates user
- UpdateUserProfileHandler → validates fields, updates aggregate

Handlers follow this pattern:

1. Validate input (already done by Request)
2. Load aggregate from repository
3. Execute domain method on aggregate
4. Save aggregate via repository
5. Dispatch domain events

## Command to Handler Mapping

| Command              | Handler                     | Repository Method Called |
| -------------------- | --------------------------- | ------------------------ |
| RegisterUser         | RegisterUserHandler         | save()                   |
| ActivateUser         | ActivateUserHandler         | findById(), save()       |
| DeactivateUser       | DeactivateUserHandler       | findById(), save()       |
| VerifyPhone          | VerifyPhoneHandler          | findById(), save()       |
| UpdateUserProfile    | UpdateUserProfileHandler    | findById(), save()       |
| AddContactPhone      | AddContactPhoneHandler      | findById(), save()       |
| RemoveContactPhone   | RemoveContactPhoneHandler   | findById(), save()       |
| AddBankDetail        | AddBankDetailHandler        | findById(), save()       |
| SetPrimaryBankDetail | SetPrimaryBankDetailHandler | findById(), save()       |
| RemoveBankDetail     | RemoveBankDetailHandler     | findById(), save()       |

## No Return Values

Commands return void or UserId (for registration). All data retrieval happens through Queries only.
