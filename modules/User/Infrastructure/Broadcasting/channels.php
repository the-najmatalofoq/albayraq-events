<?php
// filePath: modules\User\Infrastructure\Broadcasting\channels.php
declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('users.{userId}', fn($user, string $userId) => (string) $user->getAuthIdentifier() === $userId);
