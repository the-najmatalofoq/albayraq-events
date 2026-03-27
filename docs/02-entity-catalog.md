# Entity Catalog & Migration Order

## Naming Bible

| # | Module | Model Class | Table Name | Wave |
|---|--------|-------------|------------|------|
| 1 | `User` | `UserModel` | `users` | W1 |
| 2 | `Role` | `RoleModel` | `roles` | W1 |
| 3 | `ViolationType` | `ViolationTypeModel` | `violation_types` | W1 |
| 4 | `ContractRejectionReason` | `ContractRejectionReasonModel` | `contract_rejection_reasons` | W1 |
| 5 | `ReportType` | `ReportTypeModel` | `report_types` | W1 |
| 6 | `Event` | `EventModel` | `events` | W2 |
| 7 | `EventStaffingPosition` | `EventStaffingPositionModel` | `event_staffing_positions` | W3 |
| 8 | `EventStaffingGroup` | `EventStaffingGroupModel` | `event_staffing_groups` | W3 |
| 9 | `EventRoleAssignment` | `EventRoleAssignmentModel` | `event_role_assignments` | W3 |
| 10 | `EventRoleCapability` | `EventRoleCapabilityModel` | `event_role_capabilities` | W3 |
| 11 | `EventPositionApplication` | `EventPositionApplicationModel` | `event_position_applications` | W4 |
| 12 | `EventParticipation` | `EventParticipationModel` | `event_participations` | W5 |
| 13 | `EventContract` | `EventContractModel` | `event_contracts` | W6 |
| 14 | `ContractAcceptanceStep` | `ContractAcceptanceStepModel` | `contract_acceptance_steps` | W6 |
| 15 | `EventAttendance` | `EventAttendanceModel` | `event_attendance_records` | W6 |
| 16 | `ParticipationEvaluation` | `ParticipationEvaluationModel` | `participation_evaluations` | W6 |
| 17 | `ParticipationViolation` | `ParticipationViolationModel` | `participation_violations` | W6 |
| 18 | `EventParticipationBadge` | `EventParticipationBadgeModel` | `event_participation_badges` | W6 |
| 19 | `EventExperienceCertificate` | `EventExperienceCertificateModel` | `event_experience_certificates` | W6 |
| 20 | `EventTask` | `EventTaskModel` | `event_tasks` | W7 |
| 21 | `EventOperationalReport` | `EventOperationalReportModel` | `event_operational_reports` | W7 |
| 22 | `EventAssetCustody` | `EventAssetCustodyModel` | `event_asset_custodies` | W7 |
| 23 | `EventExpense` | `EventExpenseModel` | `event_expenses` | W7 |
| 24 | `EventAnnouncement` | `EventAnnouncementModel` | `event_announcements` | W7 |

---

## Dependency Waves

### Wave 1 — Foundation (Zero Dependencies)
Tables with no FK to other application tables.
- `users`, `roles`, `violation_types`, `contract_rejection_reasons`, `report_types`

### Wave 2 — Core Entity (Depends on W1)
- `events` → FK to `users` (created_by)

### Wave 3 — Event Children (Depends on W2)
- `event_staffing_positions` → FK to `events`
- `event_staffing_groups` → FK to `events`
- `event_role_assignments` → FK to `events`, `users`, `roles`
- `event_role_capabilities` → FK to `events`, `users`

### Wave 4 — Applications (Depends on W3)
- `event_position_applications` → FK to `users`, `event_staffing_positions`

### Wave 5 — Participation (Depends on W4)
- `event_participations` → FK to `users`, `events`, `event_staffing_positions`, `event_staffing_groups`

### Wave 6 — Participation Children (Depends on W5)
- `event_contracts` → FK to `event_participations`, `contract_rejection_reasons`
- `contract_acceptance_steps` → FK to `event_contracts`
- `event_attendance_records` → FK to `event_participations`, `users`
- `participation_evaluations` → FK to `event_participations`, `users`
- `participation_violations` → FK to `event_participations`, `violation_types`, `users`
- `event_participation_badges` → FK to `event_participations`
- `event_experience_certificates` → FK to `event_participations`

### Wave 7 — Event-Level Modules (Depends on W2–W5)
- `event_tasks` → FK to `events`, `users`, `event_staffing_groups`
- `event_operational_reports` → FK to `events`, `report_types`, `users`
- `event_asset_custodies` → FK to `events`, `event_participations`, `users`
- `event_expenses` → FK to `events`, `users`
- `event_announcements` → FK to `events`, `users`

---

## Module → Domain Mapping

| Module | Domain Layer | Aggregate? |
|--------|-------------|------------|
| `User` | `Modules\User\Domain\User.php` | Yes (IAM aggregate) |
| `Role` | `Modules\Role\Domain\Role.php` | No (catalog/reference) |
| `ViolationType` | `Modules\ViolationType\Domain\ViolationType.php` | No (catalog) |
| `ContractRejectionReason` | `Modules\ContractRejectionReason\Domain\ContractRejectionReason.php` | No (catalog) |
| `ReportType` | `Modules\ReportType\Domain\ReportType.php` | No (catalog) |
| `Event` | `Modules\Event\Domain\Event.php` | Yes (root aggregate) |
| `EventStaffingPosition` | `Modules\Event\Domain\Staffing\EventStaffingPosition.php` | No (child of Event) |
| `EventStaffingGroup` | `Modules\Event\Domain\Staffing\EventStaffingGroup.php` | No (child of Event) |
| `EventRoleAssignment` | — (persistence pivot only) | No |
| `EventRoleCapability` | `Modules\Event\Domain\Capability\EventRoleCapability.php` | No (config) |
| `EventPositionApplication` | `Modules\EventPositionApplication\Domain\EventPositionApplication.php` | Yes |
| `EventParticipation` | `Modules\EventParticipation\Domain\EventParticipation.php` | Yes (core aggregate) |
| `EventContract` | `Modules\EventParticipation\Domain\Contract\EventContract.php` | No (child of Participation) |
| `ContractAcceptanceStep` | `Modules\EventContract\Domain\Step\ContractAcceptanceStep.php` | No (value object) |
| `EventAttendance` | `Modules\EventParticipation\Domain\Attendance\EventAttendanceRecord.php` | No (child) |
| `ParticipationEvaluation` | `Modules\EventParticipation\Domain\Evaluation\ParticipationEvaluation.php` | No (child) |
| `ParticipationViolation` | `Modules\EventParticipation\Domain\Violation\ParticipationViolation.php` | No (child) |
| `EventParticipationBadge` | `Modules\EventParticipation\Domain\Badge\EventParticipationBadge.php` | No (derived) |
| `EventExperienceCertificate` | `Modules\EventParticipation\Domain\Certificate\EventExperienceCertificate.php` | No (derived) |
| `EventTask` | `Modules\EventTask\Domain\EventTask.php` | Yes |
| `EventOperationalReport` | `Modules\EventOperationalReport\Domain\EventOperationalReport.php` | Yes |
| `EventAssetCustody` | `Modules\Event\Domain\Custody\EventAssetCustody.php` | No (child of Event) |
| `EventExpense` | `Modules\EventExpense\Domain\EventExpense.php` | Yes |
| `EventAnnouncement` | `Modules\EventAnnouncement\Domain\EventAnnouncement.php` | Yes |
