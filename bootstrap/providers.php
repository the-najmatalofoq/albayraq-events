<?php
// filePath: bootstrap\providers.php
return [
    Modules\Shared\Infrastructure\Providers\PsrHttpServiceProvider::class,
    PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider::class,
    Modules\IAM\Infrastructure\Providers\IAMServiceProvider::class,
    App\Providers\DocumentationServiceProvider::class,
    Modules\Shared\Infrastructure\Providers\SharedServiceProvider::class,
    Modules\ViolationType\Infrastructure\Providers\ViolationTypeServiceProvider::class,
    Modules\ContractRejectionReason\Infrastructure\Providers\ContractRejectionReasonServiceProvider::class,
    Modules\ReportType\Infrastructure\Providers\ReportTypeServiceProvider::class,
    Modules\FileAttachment\Infrastructure\Providers\FileAttachmentServiceProvider::class,
    Modules\User\Infrastructure\Providers\UserServiceProvider::class,
    Modules\Event\Infrastructure\Providers\EventServiceProvider::class,
    Modules\EventStaffingPosition\Infrastructure\Providers\EventStaffingPositionServiceProvider::class,
    Modules\EventStaffingGroup\Infrastructure\Providers\EventStaffingGroupServiceProvider::class,
    Modules\EventRoleAssignment\Infrastructure\Providers\EventRoleAssignmentServiceProvider::class,
    Modules\EventRoleCapability\Infrastructure\Providers\EventRoleCapabilityServiceProvider::class,
    Modules\EventPositionApplication\Infrastructure\Providers\EventPositionApplicationServiceProvider::class,
    Modules\EventParticipation\Infrastructure\Providers\EventParticipationServiceProvider::class,
    Modules\EventStaffingPositionRequirement\Infrastructure\Providers\EventStaffingPositionRequirementServiceProvider::class,
    Modules\EventTask\Infrastructure\Providers\EventTaskServiceProvider::class,
    Modules\EventAssetCustody\Infrastructure\Providers\EventAssetCustodyServiceProvider::class,
    Modules\EventAttendance\Infrastructure\Providers\EventAttendanceServiceProvider::class,
    Modules\ParticipationEvaluation\Infrastructure\Providers\ParticipationEvaluationServiceProvider::class,
    Modules\ParticipationViolation\Infrastructure\Providers\ParticipationViolationServiceProvider::class,
    Modules\EventOperationalReport\Infrastructure\Providers\EventOperationalReportServiceProvider::class,
];

