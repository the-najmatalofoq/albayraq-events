# User Module

## Overview

The User module is the **foundation module** of the Event Management System. It manages all user accounts, employee profiles, contact phones, and bank details. This module handles user registration, profile management, identity verification, and account lifecycle (activation/deactivation).

## Key Capabilities

- User registration with phone as primary login
- Employee profile management (personal details, medical info, physical attributes)
- Emergency contact phone management
- Bank account management for payroll
- Phone verification
- Account activation/deactivation
- Soft deletion support
- Multi-language name fields (Arabic/English)

## Module Type

**Aggregate Root** — Users exist independently and are referenced by 25+ other modules (Role, EventParticipation, EventContract, etc.)

## Dependencies

- **Depends on:** None (foundation module)
- **Required by:** Role, EventParticipation, EventContract, EventAttendance, ParticipationEvaluation, EventTask, EventAnnouncement, FileAttachment, EventRoleAssignment, EventAssetCustody, EventExpense, DigitalSignature, EmployeeQuizAttempt, and 15+ other modules

## Key Terminology

| Term                   | Definition                                                |
| ---------------------- | --------------------------------------------------------- |
| **User**               | An individual with system access (worker, manager, admin) |
| **Employee Profile**   | Extended personal information (DOB, nationality, medical) |
| **Contact Phone**      | Emergency contact person and phone number                 |
| **Bank Detail**        | Bank account information for salary payments              |
| **Phone Verification** | Confirmation of phone number ownership via OTP            |
| **Soft Delete**        | User is marked deleted but data remains for auditing      |

## Module Size

- **4 database tables** (users, employee_profiles, contact_phones, bank_details)
- **4 migrations** (Wave 1: #1, Wave 2: #8, #9, #10)
- **6 domain entities/value objects**
- **8 CQRS commands**
- **2 queries**
- **11 API endpoints**

## Critical Business Rules

1. Phone number is **unique** and serves as primary login
2. Email is optional but must be unique if provided
3. National ID is optional but must be unique if provided
4. New users are created with `is_active = false` (must be activated by admin)
5. Phone must be verified before certain operations
6. Users can have multiple bank accounts but only one `is_primary = true`
7. Soft-deleted users keep their data but cannot log in
8. User deletion cascades to employee_profile, contact_phones, bank_details
9. Role assignments are **preserved** for auditing when user is deleted

## File Structure

```

modules/User/
├── Application/
│ └── Command/
│ ├── RegisterUser/
│ │ ├── RegisterUserCommand.php
│ │ └── RegisterUserHandler.php
│ ├── ActivateUser/
│ │ ├── ActivateUserCommand.php
│ │ └── ActivateUserHandler.php
│ ├── UpdateUserProfile/
│ │ ├── UpdateUserProfileCommand.php
│ │ └── UpdateUserProfileHandler.php
│ ├── AddContactPhone/
│ │ ├── AddContactPhoneCommand.php
│ │ └── AddContactPhoneHandler.php
│ ├── AddBankDetail/
│ │ ├── AddBankDetailCommand.php
│ │ └── AddBankDetailHandler.php
│ ├── SetPrimaryBankDetail/
│ │ ├── SetPrimaryBankDetailCommand.php
│ │ └── SetPrimaryBankDetailHandler.php
│ └── VerifyPhone/
│ ├── VerifyPhoneCommand.php
│ └── VerifyPhoneHandler.php
├── Domain/
│ ├── User.php (aggregate root)
│ ├── Enum/
│ │ └── GenderEnum.php
│ ├── Exception/
│ │ ├── UserNotFoundException.php
│ │ ├── DuplicatePhoneException.php
│ │ └── InactiveUserException.php
│ ├── Repository/
│ │ └── UserRepositoryInterface.php
│ └── ValueObject/
│ ├── UserId.php
│ ├── EmployeeProfile.php
│ ├── ContactPhone.php
│ └── BankDetail.php
├── Infrastructure/
│ ├── Persistence/
│ │ ├── Eloquent/
│ │ │ ├── UserModel.php
│ │ │ ├── EmployeeProfileModel.php
│ │ │ ├── ContactPhoneModel.php
│ │ │ ├── BankDetailModel.php
│ │ │ └── EloquentUserRepository.php
│ │ ├── Migrations/
│ │ │ ├── 2026_03_25_100000_create_users_table.php
│ │ │ ├── 2026_03_25_100100_create_employee_profiles_table.php
│ │ │ ├── 2026_03_25_100200_create_contact_phones_table.php
│ │ │ └── 2026_03_25_100300_create_bank_details_table.php
│ │ ├── Reflectors/
│ │ │ └── UserReflector.php
│ │ └── Seeders/
│ │ └── UserSeeder.php
│ ├── Providers/
│ │ └── UserServiceProvider.php
│ └── Routes/
│ └── api.php
└── Presentation/
└── Http/
├── Action/
│ ├── RegisterUserAction.php
│ ├── VerifyPhoneAction.php
│ ├── GetProfileAction.php
│ ├── UpdateProfileAction.php
│ ├── AddContactPhoneAction.php
│ ├── RemoveContactPhoneAction.php
│ ├── ListBankDetailsAction.php
│ ├── AddBankDetailAction.php
│ ├── SetPrimaryBankDetailAction.php
│ ├── RemoveBankDetailAction.php
│ ├── GetUserAction.php
│ └── ListUsersAction.php
├── Presenter/
│ ├── UserPresenter.php
│ ├── EmployeeProfilePresenter.php
│ ├── ContactPhonePresenter.php
│ └── BankDetailPresenter.php
└── Request/
├── RegisterUserRequest.php
├── VerifyPhoneRequest.php
├── UpdateProfileRequest.php
├── AddContactPhoneRequest.php
├── AddBankDetailRequest.php
└── ListUsersRequest.php

```

## Quick Reference

- **Migration Wave:** Wave 1 (users) + Wave 2 (profiles, phones, bank)
- **Primary Key Type:** UUID (auto-generated)
- **Soft Deletes:** Yes (users table only)
- **Translation:** JSON columns with `{ar, en}` structure
- **Authentication:** JWT (phone + password)
- **Module Status:** ✅ Complete (ready for production)
