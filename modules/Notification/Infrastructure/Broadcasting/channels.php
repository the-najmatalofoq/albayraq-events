<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel;
use Modules\User\Infrastructure\Persistence\Eloquent\UserModel;

Broadcast::channel('user.{userId}', function (UserModel $user, string $userId) {
    return (string) $user->id === $userId;
});

Broadcast::channel('event.{eventId}', function (UserModel $user, string $eventId) {
    $hasRole = EventRoleAssignmentModel::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->exists();

    $hasGlobalRole = $user->roles()->whereIn('slug', [
        'system_controller',
        'general_manager',
        'operations_manager'
    ])->exists();

    return $hasRole || $hasGlobalRole;
});

Broadcast::channel('event.{eventId}.attendance', function (UserModel $user, string $eventId) {
    $assignment = EventRoleAssignmentModel::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->with('role')
        ->first();

    if (!$assignment) {
        return false;
    }

    if (!$assignment->role) {
        return false;
    }

    return $assignment->role->level !== 'individual';
});

Broadcast::channel('event.{eventId}.location', function (UserModel $user, string $eventId) {
    $assignment = EventRoleAssignmentModel::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->with('role')
        ->first();

    if (!$assignment) {
        return false;
    }

    if ($assignment->role?->level === 'individual') {
        return false;
    }

    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('event.{eventId}.group.{groupId}', function (UserModel $user, string $eventId, string $groupId) {
    $participation = Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->where('group_id', $groupId)
        ->exists();

    if ($participation) {
        return true;
    }

    $assignment = EventRoleAssignmentModel::where('user_id', $user->id)
        ->where('event_id', $eventId)
        ->with('role')
        ->first();

    if (!$assignment) {
        return false;
    }

    return $assignment->role->level !== 'individual';
});
