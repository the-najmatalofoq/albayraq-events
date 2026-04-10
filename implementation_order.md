# Project Module Execution Order

This document outlines the systematic order for implementing and migrating modules in the Event Management System. The order is strictly defined by architectural dependencies to ensure data integrity and avoid foreign key constraint violations.

---

## 🏗️ Wave 1: Foundation (Core Identity & Configuration)
These modules are standalone or depend only on the Shared module. They provide the identity and authorization layer.

1.  **Shared Module**: Base utilities, `attachments` table, and common value objects.
2.  **User Module (Core)**: The `users` table. Referenced by 25+ external tables.
3.  **Role Module**: Global role definition (`roles`) and assignment (`role_user`).
4.  **IAM Module**: Authentication system (Login, Register, JWT, OTP Verification).
5.  **ViolationType Module**: Lookup data for participation violations.
6.  **ContractRejectionReason Module**: Lookup data for rejected agreements.
7.  **ReportType Module**: Lookup data for operational reports.

---
    
## 📅 Wave 2: User Profiles & Core Business Entity
These modules extend the user identity and introduce the primary business record: the Event.

8.  **User: EmployeeProfile**: One-to-one extension of user details.
9.  **User: ContactPhone**: Emergency contacts for users.
10. **User: BankDetail**: Payment information for financial dispersal.
11. **Event Module**: The primary aggregate root. Everything (tasks, attendance, finance) is scoped to an event.
12. **WorkSchedule Module**: Manages daily working hour windows for events.

---

## 👥 Wave 3: Staffing Definition
Defining the organization and roles required for a specific event.

13. **EventStaffingPosition**: Job titles within an event (e.g., Security, Usher).
14. **Wage Module**: Pay rates and types (Daily/Monthly) linked to positions.
15. **Quiz Module**: Assessments used for worker recruitment.
16. **EventStaffingGroup**: Teams/Sectors within an event.
17. **EventShift**: Temporal shifts defined for specific positions (Wave 3).
18. **EventRoleAssignment**: Event-scoped permissions (e.g., Project Manager).
19. **EventRoleCapability**: Specific permissions for event roles.

---

## 📝 Wave 4: Candidates & Recruitment
Handling the transition from candidate to potential worker.

20. **Question Module**: The question bank for recruitment quizzes.
21. **EventPositionApplication**: Tracks candidates applying for specific event positions.
22. **EventJoinRequest**: Direct request from staff to join an event with conflict validation.

---

## 🤝 Wave 5: Participation & Legal
Closing the loop on hiring and formalizing the worker-event relationship.

23. **EventParticipation**: The central bridge between `User`, `Event`, and `Position`.
24. **DigitalSignature Module**: Shared service for capturing signatures.
25. **EventContract Module**: Specific legal agreements for each participation.
26. **ContractAcceptanceStep**: Workflow steps for contract review and signing.

---

## ⚡ Wave 6: Operations & Feedback
The "Live" phase of an event where work is tracked and evaluated.

27. **EventShiftAssignment**: Assigning specific participations to defined shifts.
28. **EmployeeQuizAttempt**: Performance or training quiz results.
29. **EventAttendance**: Check-in/out logs mapped to participation.
30. **AttendanceBarcode**: Tools for physical check-in verification.
31. **ParticipationEvaluation**: Performance scores and manager feedback.
32. **ParticipationViolation**: Records of worker disciplinary actions.
33. **Discount Module**: Wage deductions based on violations or other factors.
34. **EventParticipationBadge**: Visual status indicators for workers.
35. **EventExperienceCertificate**: Final documents generated upon event closure.

---

## 📊 Wave 7: Event Management & Financials
Administrative and oversight modules for event health and costs.

36. **EventTask**: Specific assignments given to workers or groups.
37. **EventOperationalReport**: Daily reports submitted by event management.
38. **EventAssetCustody**: Tracking equipment provided to workers.
39. **EventExpenseModule**: Financial claims and costs submitted by managers.
40. **EventAnnouncement**: Mass communication broadcast to event participants.

---

## 🏁 Verification Order
1.  **Migration Status**: Run `php artisan migrate:status` to ensure Wave 1 is fully "Yes" before Wave 2.
2.  **Seeder Verification**: Ensure `UserSeeder` runs before `EventSeeder`, and `EventSeeder` before `ParticipationSeeder`.
